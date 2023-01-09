<?php

namespace app\modules\exports\services;

use CI_DB_mysqli_driver;

class ExpenseCSVExport extends CSVExport
{
    private array $paymentGateways;

    private string $baseCurrency;

    private array $excludedFields = [
        'tax',
        'tax2',
        'category',
        'recurring',
        'recurring_type',
        'repeat_every',
        'cycles',
        'total_cycles',
        'custom_recurring',
        'last_recurring',
        'last_recurring_date',
        'create_invoice',
        'send_invoice',
        'recurring_from',
        'payment_mode',
        'paymentmode',
        'create_invoice_billable',
        'send_invoice_to_customer',
        'currency',
        'project_id',
        'addedfrom',
        'clientid',

        'invoice_status',
        'invoice_number',
        'invoice_number_format',
        'invoice_date',
        'invoice_prefix',
    ];

    public function __construct(?string $fromDate, ?string $toDate)
    {
        parent::__construct($fromDate, $toDate, 'expenses');
        $this->ci->load->model('payment_modes_model');
        $this->paymentGateways = collect($this->ci->payment_modes_model->get_payment_gateways(true))->pluck('name', 'id')->toArray();
        $this->baseCurrency    = get_base_currency()->name;
    }

    public function queryData(): CI_DB_mysqli_driver
    {
        $select = db_prefix() . 'invoices.date as invoice_date,' . db_prefix() . 'invoices.number as invoice_number,' . db_prefix() . 'invoices.prefix as invoice_prefix,' . db_prefix() . 'invoices.number_format as invoice_number_format,' . db_prefix() . 'invoices.status as invoice_status,(CASE WHEN ' . db_prefix() . 'payment_modes.name IS NULL THEN ' . db_prefix() . 'expenses.paymentmode ELSE ' . db_prefix() . 'payment_modes.name END) as payment_mode,'
            . db_prefix() . 'expenses_categories.name as expense_category,'
            . db_prefix() . 'currencies.name as currency,'
            . db_prefix() . 'taxes.name as tax_name, ' . db_prefix() . 'taxes.taxrate as tax_rate,' . db_prefix() . 'taxes_2.name as tax_name_2, '
            . db_prefix() . 'taxes_2.taxrate as tax_rate_2,'
            . db_prefix() . 'projects.name as project,'
            . 'CONCAT(' . db_prefix() . 'staff.firstname, " ", ' . db_prefix() . 'staff.lastname) as created_by,'
            . get_sql_select_client_company('customer');

        $this->ci->db->select(prefixed_table_fields_array(db_prefix() . 'expenses', true, $this->excludedFields) . ", $select");
        $this->selectCustomFields(db_prefix() . 'expenses.id');
        $this->ci->db->join(db_prefix() . 'payment_modes', db_prefix() . 'payment_modes.id = ' . db_prefix() . 'expenses.paymentmode', 'left');
        $this->ci->db->join(db_prefix() . 'taxes', db_prefix() . 'taxes.id = ' . db_prefix() . 'expenses.tax', 'left');
        $this->ci->db->join(db_prefix() . 'taxes as ' . db_prefix() . 'taxes_2', db_prefix() . 'taxes_2.id =' . db_prefix() . 'expenses.tax2', 'left');
        $this->ci->db->join(db_prefix() . 'expenses_categories', db_prefix() . 'expenses_categories.id = ' . db_prefix() . 'expenses.category');
        $this->ci->db->join(db_prefix() . 'currencies', db_prefix() . 'currencies.id = ' . db_prefix() . 'expenses.currency', 'left');
        $this->ci->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'expenses.clientid', 'left');
        $this->ci->db->join(db_prefix() . 'projects', db_prefix() . 'projects.id = ' . db_prefix() . 'expenses.project_id', 'left');
        $this->ci->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'expenses.addedfrom', 'left');
        $this->ci->db->join(db_prefix() . 'invoices', db_prefix() . 'invoices.id = ' . db_prefix() . 'expenses.invoiceid', 'left');
        $this->applyDateFilter(db_prefix() . 'expenses.dateadded');

        return $this->ci->db->from(db_prefix() . 'expenses');
    }

    protected function formatHeading(string $header): string
    {
        switch ($header) {
            case 'refrence_no':
                return 'Reference';
            case 'clientid':
                return 'Client';
            case 'invoiceid':
                return 'Invoice Number';
            case 'dateadded':
                return 'Date created';
            default:
                return parent::formatHeading($header);
        }
    }

    protected function formatRow(string $name, $value, array $row)
    {
        switch ($name) {
            case 'currency':
                return $value ?: $this->baseCurrency;
            case 'invoiceid':
                 return format_invoice_number((object) [
                    'id'            => $value,
                    'date'          => $row['invoice_date'],
                    'number_format' => $row['invoice_number_format'],
                    'number'        => $row['invoice_number'],
                    'prefix'        => $row['invoice_prefix'],
                    'status'        => $row['invoice_status'],
                ]);
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
