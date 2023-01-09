<?php

namespace app\modules\exports\services;

use CI_DB_mysqli_driver;

class PaymentCSVExport extends CSVExport
{
    private array $paymentGateways;

    private string $baseCurrency;

    private array $excludedFields = [
        'paymentmode',
        'paymentmethod',
        'invoice_status',
        'invoice_number',
        'invoice_number_format',
        'invoice_date',
        'invoice_prefix',
    ];

    public function __construct(?string $fromDate, ?string $toDate)
    {
        parent::__construct($fromDate, $toDate);
        $this->ci->load->model('payment_modes_model');
        $this->paymentGateways = collect($this->ci->payment_modes_model->get_payment_gateways(true))->pluck('name', 'id')->toArray();
        $this->baseCurrency    = get_base_currency()->name;
    }

    public function queryData(): CI_DB_mysqli_driver
    {
        $select = db_prefix() . 'invoices.date as invoice_date,' . db_prefix() . 'invoices.number as invoice_number,' . db_prefix() . 'invoices.prefix as invoice_prefix,' . db_prefix() . 'invoices.number_format as invoice_number_format,' . db_prefix() . 'invoices.status as invoice_status,' . db_prefix() . 'currencies.name as currency,'
            . '(CASE WHEN ' . db_prefix() . 'payment_modes.name IS NULL THEN ' . db_prefix() . 'invoicepaymentrecords.paymentmode ELSE ' . db_prefix() . 'payment_modes.name END) as payment_mode,';
        $this->ci->db->select(prefixed_table_fields_array(db_prefix() . 'invoicepaymentrecords', true, $this->excludedFields) . ',' . $select);
        $this->ci->db->join(db_prefix() . 'invoices', db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $this->ci->db->join(db_prefix() . 'payment_modes', db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode', 'left');
        $this->ci->db->join(db_prefix() . 'currencies', db_prefix() . 'currencies.id = ' . db_prefix() . 'invoices.currency');
        $this->applyDateFilter(db_prefix() . 'invoicepaymentrecords.daterecorded');

        return $this->ci->db->from(db_prefix() . 'invoicepaymentrecords');
    }

    protected function formatHeading(string $header): string
    {
        if ($header == 'invoiceid') {
            return 'Invoice Number';
        }

        return parent::formatHeading($header);
    }

    protected function formatRow(string $name, $value, array $row)
    {
        switch ($name) {
            case 'invoiceid':
                return format_invoice_number((object) [
                    'id'            => $value,
                    'date'          => $row['invoice_date'],
                    'number_format' => $row['invoice_number_format'],
                    'number'        => $row['invoice_number'],
                    'prefix'        => $row['invoice_prefix'],
                    'status'        => $row['invoice_status'],
                ]);
            case 'currency':
                return $value ?: $this->baseCurrency;
            case 'payment_mode':
                return array_key_exists($value, $this->paymentGateways) ? $this->paymentGateways[$value] : $value;
            default:
                return parent::formatRow($name, $value, $row);
        }
    }

    public function excludedFields() : array
    {
        return $this->excludedFields;
    }
}
