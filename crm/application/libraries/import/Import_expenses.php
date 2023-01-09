<?php

require_once(APPPATH . 'libraries/import/App_import.php');

class Import_expenses extends App_import
{
    protected $notImportableFields;

    protected $requiredFields = ['category', 'amount', 'date'];

    protected $categories = null;

    protected $paymentmodes = null;

    protected $gateways = null;

    public function __construct()
    {
        $this->addImportGuidelinesInfo('In the column <b>Payment Mode</b>, you <b>must</b> add either the <b>Payment NAME or the Payment ID</b>, which you can get them by navigating to <a href="' . admin_url('paymentmodes') . '" target="_blank">Setup->Finance->Payment Modes</a>.');
        $this->addImportGuidelinesInfo('In the column <b>Tax</b> and <b>Tax2</b>, you <b>must</b> add either the <b>TAX NAME or the TAX ID</b>, which you can get them by navigating to <a href="' . admin_url('taxes') . '" target="_blank">Setup->Finance->Taxes</a>.');
        $this->addImportGuidelinesInfo('In the column <b>Category</b>, you <b>must</b> add either the <b>Expense Category NAME or the Expense Category ID</b>, which you can get them by navigating to <a href="' . admin_url('expenses/categories') . '" target="_blank">Setup->Finance->Expense Category</a>.');

        $this->notImportableFields = hooks()->apply_filters('not_importable_expense_fields', [
            'id',
            'currency',
            'project_id',
            'invoiceid',
            'recurring_type',
            'repeat_every',
            'recurring',
            'cycles',
            'total_cycles',
            'custom_recurring',
            'last_recurring_date',
            'create_invoice_billable',
            'send_invoice_to_customer',
            'recurring',
            'recurring_from',
            'dateadded',
            'addedfrom',
        ]);

        parent::__construct();

        $this->ci->load->model('payment_modes_model');
        $this->gateways = collect($this->ci->payment_modes_model->get_payment_gateways(true));
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

                if ($databaseFields[$i] == 'amount' && !is_numeric($row[$i])) {
                    $row[$i] = 0;
                } elseif ($databaseFields[$i] == 'category') {
                    $row[$i] = $this->categoryValue($row[$i]);
                } elseif ($databaseFields[$i] == 'tax' || $databaseFields[$i] == 'tax2') {
                    $row[$i] = $this->taxValue($row[$i]);
                } elseif ($databaseFields[$i] == 'paymentmode' && $row[$i] != '') {
                    $row[$i] = $this->getPaymentModeValue($row[$i]);
                } elseif ($databaseFields[$i] == 'clientid' && $row[$i] != '' && !is_numeric($row[$i])) {
                    $row[$i] = $this->getClientValue($row[$i]);
                }
                $insert[$databaseFields[$i]] = $row[$i];
            }

            $insert = $this->trimInsertValues($insert);

            if (count($insert) > 0) {
                $this->incrementImported();

                if (!empty($insert['tax2']) && empty($insert['tax'])) {
                    $insert['tax']  = $insert['tax2'];
                    $insert['tax2'] = 0;
                }

                $insert['dateadded'] = date('Y-m-d H:i:s');
                $insert['addedfrom'] = get_staff_user_id();
                if ($insert['clientid'] == 0) {
                    $insert['currency'] = get_base_currency()->id;
                    $insert['billable'] = 0;
                } else {
                    $insert['currency'] = $this->getClientCurrency($insert['clientid']);
                    $insert['billable'] = filter_var($insert['billable'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                }

                $id = null;

                if (!$this->isSimulation()) {
                    $this->ci->db->insert(db_prefix() . 'expenses', $insert);
                    $id = $this->ci->db->insert_id();
                } else {
                    $this->simulationData[$rowNumber] = $this->formatValuesForSimulation($insert);
                }

                $this->handleCustomFieldsInsert($id, $row, $i, $rowNumber, 'expenses');
            }

            if ($this->isSimulation() && $rowNumber >= $this->maxSimulationRows) {
                break;
            }
        }
    }

    private function categoryValue($value)
    {
        if (!is_numeric($value)) {
            $category = $this->getCategoryBy('name', $value);
            $value    = $category ? $category->id : 0;
        }

        return $value;
    }

    private function taxValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $tax   = $this->getTaxBy('name', $value);
                $value = $tax ? $tax->id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }

    private function getTaxBy($field, $idOrName)
    {
        $this->ci->db->where($field, $idOrName);

        return $this->ci->db->get(db_prefix() . 'taxes')->row();
    }

    private function getPaymentModeValue($value)
    {
        $mode = $this->getPaymentMode($value);

        if (is_array($mode)) {
            return $mode['id'];
        } elseif (is_object($mode)) {
            return $mode->id;
        }

        return null;
    }

    private function getPaymentMode($value)
    {
        if (empty($value) || $value == '0') {
            return null;
        }

        $mode = null;

        if (is_numeric($value)) {
            $mode = $this->getPaymentModeBy('id', $value);
        }

        if (!$mode) {
            $mode = $this->getPaymentModeBy('name', $value);
        }

        if ($mode) {
            return $mode;
        }

        $mode = $this->gateways->where('name', $value)->first();

        if ($mode) {
            return $mode;
        }

        return $this->gateways->where('id', $value)->first();
    }

    private function getPaymentModeBy(
        $field,
        $value
    ) {
        if (!$this->paymentmodes) {
            $this->paymentmodes = collect(
                $this->ci->db->get(db_prefix() . 'payment_modes')->result_object()
            );
        }

        return $this->paymentmodes->where($field, $value)->first();
    }

    private function getClientValue($value)
    {
        if (($value != '' && $value != '0') && !is_numeric($value)) {
            $client = $this->getClientBy('company', $value);

            return $client ? $client->userid : 0;
        }

        return $value;
    }

    private function formatValuesForSimulation($values)
    {
        foreach ($values as $column => $val) {
            if ($column == 'category' && !empty($val) && is_numeric($val)) {
                $category = $this->getCategoryBy('id', $val);
                if ($category) {
                    $values[$column] = $category->name;
                }
            } elseif (($column == 'tax' || $column == 'tax2') && !empty($val) && is_numeric($val)) {
                $tax = $this->getTaxBy('id', $val);
                if ($tax) {
                    $values[$column] = $tax->name . ' (' . $tax->taxrate . '%)';
                }
            } elseif ($column == 'paymentmode' && !empty($val)) {
                $mode = $this->getPaymentMode($val);

                if ($mode) {
                    $values[$column] = is_array($mode) ? $mode['name'] : $mode->name;
                }
            } elseif ($column == 'clientid' && !empty($val) && is_numeric($val)) {
                $client = $this->getClientBy('userid', $val);
                if ($client) {
                    $values[$column] = $client->company;
                }
            } elseif ($column == 'billable') {
                $values[$column] = $val ? 'Yes' : 'No';
            }
        }

        return $values;
    }

    private function getCategoryBy($field, $value)
    {
        if (!$this->categories) {
            $this->categories = collect(
                $this->ci->db->get(db_prefix() . 'expenses_categories')->result_object()
            );
        }

        return $this->categories->where($field, $value)->first();
    }

    private function getClientBy($field, $idOrName)
    {
        return $this->ci->db->where($field, $idOrName)->get(db_prefix() . 'clients')->row();
    }

    private function getClientCurrency($clientId)
    {
        $client = $this->getClientBy('userid', $clientId);
        $base   = get_base_currency()->id;

        if (!$client) {
            return $base;
        }

        return $client->default_currency ? $client->default_currency : $base;
    }

    public function formatFieldNameForHeading($field)
    {
        switch (strtolower($field)) {
            case 'clientid':
                return 'Customer';
            case 'paymentmode':
                return 'Payment Mode';
            case 'date':
                return 'Date';
            default:
                return parent::formatFieldNameForHeading($field);
        }
    }

    protected function failureRedirectURL()
    {
        return admin_url('expenses/import');
    }
}
