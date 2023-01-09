<?php

namespace app\modules\exports\services;

use CI_DB_mysqli_driver;

class LeadCSVExport extends CSVExport
{
    private array $excludedFields = [
        'hash',
        'from_form_id',
        'client_id',
        'addedfrom',
        'assigned',
        'leadorder',
        'is_imported_from_email_integration',
        'email_integration_uid',
        'status',
        'source',
    ];

    private array $countriesMap;

    private string $defaultLanguage;

    public function __construct(?string $fromDate, ?string $toDate)
    {
        parent::__construct($fromDate, $toDate, 'leads');
        $this->countriesMap    = collect(get_all_countries())->pluck('short_name', 'country_id')->toArray();
        $this->defaultLanguage = get_option('active_language');
    }

    public function queryData(): CI_DB_mysqli_driver
    {
        $select = db_prefix() . 'leads_status.name as status,'
            . db_prefix() . 'leads_sources.name as source,'
            . db_prefix() . 'web_to_lead.name as web_to_lead_form,'
            . 'CONCAT(' . db_prefix() . 'staff.firstname, " ", ' . db_prefix() . 'staff.lastname) as created_by,'
            . 'CONCAT(staff_leads.firstname, " ", staff_leads.lastname) as assigned_staff,'
            . get_sql_select_client_company('company_name');

        $this->ci->db->select(prefixed_table_fields_array(db_prefix() . 'leads', true, $this->excludedFields) . ", $select");
        $this->selectCustomFields(db_prefix() . 'leads.id');
        $this->ci->db->join(db_prefix() . 'leads_status', db_prefix() . 'leads_status.id=' . db_prefix() . 'leads.status', 'left');
        $this->ci->db->join(db_prefix() . 'leads_sources', db_prefix() . 'leads_sources.id=' . db_prefix() . 'leads.source', 'left');
        $this->ci->db->join(db_prefix() . 'web_to_lead', db_prefix() . 'web_to_lead.id=' . db_prefix() . 'leads.from_form_id', 'left');
        $this->ci->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'leads.client_id', 'left');
        $this->ci->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'leads.addedfrom', 'left');
        $this->ci->db->join(db_prefix() . 'staff staff_leads', 'staff_leads.staffid = ' . db_prefix() . 'leads.assigned', 'left');
        $this->applyDateFilter(db_prefix() . 'leads.dateadded');

        return $this->ci->db->from(db_prefix() . 'leads');
    }

    protected function formatHeading(string $header): string
    {
        switch ($header) {
            case 'dateadded':
                return 'Date created';
            case 'dateassigned':
                return 'Date assigned';
            case 'lastcontact':
                return 'Last contact';
            default:
                return parent::formatHeading($header);
        }
    }

    protected function formatRow(string $name, $value, array $row)
    {
        switch ($name) {
            case 'country':
                return array_key_exists($value, $this->countriesMap) ? $this->countriesMap[$value] : $value;
            case 'default_language':
                return $value ?: $this->defaultLanguage;
            default:
                return parent::formatRow($name, $value, $row);
        }
    }
}
