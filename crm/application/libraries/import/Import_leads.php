<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/import/App_import.php');

class Import_leads extends App_import
{
    private $uniqueValidationFields = [];

    protected $notImportableFields = [];

    protected $requiredFields = ['name'];

    protected $sources;

    protected $statuses;

    public function __construct()
    {
        $this->notImportableFields = hooks()->apply_filters('not_importable_leads_fields', ['id', 'assigned', 'dateadded', 'last_status_change', 'addedfrom', 'leadorder', 'date_converted', 'lost', 'junk', 'is_imported_from_email_integration', 'email_integration_uid', 'is_public', 'dateassigned', 'client_id', 'lastcontact', 'last_lead_status', 'from_form_id', 'default_language', 'hash']);

        $uniqueValidationFields = json_decode(get_option('lead_unique_validation'));

        if (count($uniqueValidationFields) > 0) {
            $this->uniqueValidationFields = $uniqueValidationFields;
            $message                      = '';

            foreach ($uniqueValidationFields as $key => $field) {
                if ($key === 0) {
                    $message .= 'Based on your leads <b class="text-danger">unique validation</b> configured <a href="' . admin_url('settings?group=leads#unique_validation_wrapper') . '" target="_blank">options</a>, the lead <b>won\'t</b> be imported if:<br />';
                }

                $message .= '<br />&nbsp;&nbsp;&nbsp; - Lead <b>' . $field . '</b> already exists OR';
            }

            if ($message != '') {
                $message = substr($message, 0, -3);
            }

            $message .= '<br /><br />If you still want to import all leads, uncheck all unique validation field';

            $this->addImportGuidelinesInfo($message);
        }

        parent::__construct();

        $this->sources  = $this->ci->db->get('tblleads_sources')->result_array();
        $this->statuses = $this->ci->db->get('tblleads_status')->result_array();
    }

    public function perform()
    {
        $this->initialize();

        $databaseFields      = $this->getImportableDatabaseFields();
        $totalDatabaseFields = count($databaseFields);

        foreach ($this->getRows() as $rowNumber => $row) {
            $insert = [];
            for ($i = 0; $i < $totalDatabaseFields; $i++) {
                $row[$i] = $this->checkNullValueAddedByUser($row[$i]);

                if ($databaseFields[$i] == 'name' && empty($row[$i])) {
                    $row[$i] = '/';
                } elseif ($databaseFields[$i] == 'country') {
                    $row[$i] = $this->countryValue($row[$i]);
                } elseif ($databaseFields[$i] == 'source') {
                    $row[$i] = $this->sourceValue($row[$i]);
                } elseif ($databaseFields[$i] == 'status') {
                    $row[$i] = $this->statusValue($row[$i]);
                }

                $insert[$databaseFields[$i]] = $row[$i];
            }

            $insert = $this->trimInsertValues($insert);

            if (count($insert) > 0) {
                if ($this->isDuplicateLead($insert)) {
                    continue;
                }

                $this->incrementImported();

                $id = null;

                if (!$this->isSimulation()) {
                    if (!isset($insert['dateadded'])) {
                        $insert['dateadded'] = date('Y-m-d H:i:s');
                    }

                    if (!isset($insert['addedfrom'])) {
                        $insert['addedfrom'] = get_staff_user_id();
                    }

                    if ($this->ci->input->post('responsible')) {
                        $insert['assigned'] = $this->ci->input->post('responsible');
                    }

                    $tags = '';

                    if (isset($insert['tags']) || is_null($insert['tags'])) {
                        if (!is_null($insert['tags'])) {
                            $tags = $insert['tags'];
                        }

                        unset($insert['tags']);
                    }

                    $this->ci->db->insert('leads', $insert);
                    $id = $this->ci->db->insert_id();

                    if ($id) {
                        handle_tags_save($tags, $id, 'lead');
                    }
                } else {
                    $this->simulationData[$rowNumber] = $this->formatValuesForSimulation($insert);
                }

                $this->handleCustomFieldsInsert($id, $row, $i, $rowNumber, 'leads');
            }

            if ($this->isSimulation() && $rowNumber >= $this->maxSimulationRows) {
                break;
            }
        }
    }

    protected function findSource($id)
    {
        foreach ($this->sources as $source) {
            if ($source['name'] == $id || $source['id'] == $id) {
                return $source;
            }
        }
    }

    protected function findStatus($id)
    {
        foreach ($this->statuses as $status) {
            if ($status['name'] == $id || $status['id'] == $id) {
                return $status;
            }
        }
    }

    protected function statusValue($value)
    {
        return $this->findStatus($value)['id'] ?? $this->ci->input->post('status');
    }

    protected function sourceValue($value)
    {
        return $this->findSource($value)['id'] ?? $this->ci->input->post('source');
    }

    protected function tags_formatSampleData()
    {
        return 'tag1,tag2';
    }

    public function formatFieldNameForHeading($field)
    {
        if (strtolower($field) == 'title') {
            return 'Position';
        }

        return parent::formatFieldNameForHeading($field);
    }

    protected function email_formatSampleData()
    {
        return uniqid() . '@example.com';
    }

    protected function failureRedirectURL()
    {
        return admin_url('leads/import');
    }

    private function isDuplicateLead($data)
    {
        foreach ($this->uniqueValidationFields as $field) {
            if ((isset($data[$field]) && $data[$field] != '') && total_rows('leads', [$field => $data[$field]]) > 0) {
                return true;
            }
        }

        return false;
    }

    private function formatValuesForSimulation($values)
    {
        foreach ($values as $column => $val) {
            if ($column == 'country' && !empty($val) && is_numeric($val)) {
                $country = $this->getCountry(null, $val);
                if ($country) {
                    $values[$column] = $country->short_name;
                }
            } elseif ($column == 'source') {
                $values[$column] = $this->findSource($val)['name'] ?? 'N/A';
            } elseif ($column == 'status') {
                $values[$column] = $this->findStatus($val)['name'] ?? 'N/A';
            }
        }

        return $values;
    }

    private function getCountry($search = null, $id = null)
    {
        if ($search) {
            $this->ci->db->where('iso2', $search)
            ->or_where('short_name', $search)
            ->or_where('long_name', $search);
        } else {
            $this->ci->db->where('country_id', $id);
        }

        return  $this->ci->db->get('countries')->row();
    }

    private function countryValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $country = $this->getCountry($value);
                $value   = $country ? $country->country_id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }
}