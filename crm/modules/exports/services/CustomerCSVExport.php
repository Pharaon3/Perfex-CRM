<?php

namespace app\modules\exports\services;

use CI_DB_mysqli_driver;

class CustomerCSVExport extends CSVExport
{
    private string $defaultLanguage;

    private string $baseCurrency;

    private array $countriesMap;

    public function __construct(?string $fromDate, ?string $toDate)
    {
        parent::__construct($fromDate, $toDate, 'customers');
        $this->baseCurrency    = get_base_currency()->name;
        $this->defaultLanguage = get_option('active_language');
        $this->countriesMap    = collect(get_all_countries())->pluck('short_name', 'country_id')->toArray();
    }

    private array $excludedFields = [
        'leadid',
        'addedfrom',
        'company',
        'show_primary_contact',
        'default_language',
    ];

    public function queryData(): CI_DB_mysqli_driver
    {
        $select = get_sql_select_client_company('company_name')
            . ',' . db_prefix() . 'leads.name as lead_name,' . db_prefix() . 'currencies.name as default_currency'
            . ',CONCAT(' . db_prefix() . 'staff.firstname, " ", ' . db_prefix() . 'staff.lastname) as created_by';
        $this->ci->db->select(prefixed_table_fields_array(db_prefix() . 'clients', true, $this->excludedFields) . ',' . $select);
        $this->selectCustomFields(db_prefix() . 'clients.userid');
        $this->ci->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.userid = ' . db_prefix() . 'clients.userid AND is_primary = 1', 'left');
        $this->ci->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'clients.addedfrom', 'left');
        $this->ci->db->join(db_prefix() . 'leads', db_prefix() . 'leads.id = ' . db_prefix() . 'clients.leadid', 'left');
        $this->ci->db->join(db_prefix() . 'currencies', db_prefix() . 'currencies.id = ' . db_prefix() . 'clients.default_currency', 'left');
        $this->applyDateFilter(db_prefix() . 'clients.datecreated');

        return $this->ci->db->from(db_prefix() . 'clients');
    }

    protected function formatRow(string $name, $value, array $row)
    {
        switch ($name) {
            case 'country':
            case 'billing_country':
            case 'shipping_country':
                return array_key_exists($value, $this->countriesMap) ? $this->countriesMap[$value] : $value;
            case 'default_currency':
                return $value ?: $this->baseCurrency;
            case 'default_language':
                return $value ?: $this->defaultLanguage;
            default:
                return parent::formatRow($name, $value, $row);
        }
    }
}
