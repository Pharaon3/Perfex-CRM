<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Taxes_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get tax by id
     * @param  mixed $id tax id
     * @return mixed     if id passed return object else array
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'taxes')->row();
        }
        $this->db->order_by('taxrate', 'ASC');

        return $this->db->get(db_prefix() . 'taxes')->result_array();
    }

    /**
     * Add new tax
     * @param array $data tax data
     * @return boolean
     */
    public function add($data)
    {
        unset($data['taxid']);
        $data['name']    = trim($data['name']);
        $data['taxrate'] = trim($data['taxrate']);

        $data = hooks()->apply_filters('before_tax_created', $data);

        $this->db->insert('taxes', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Tax Added [ID: ' . $insert_id . ', ' . $data['name'] . ']');

            hooks()->do_action('after_tax_created', [
                'id'   => $insert_id,
                'data' => $data,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Edit tax
     * @param  array $data tax data
     * @return boolean
     */
    public function edit($data)
    {
        if (total_rows(db_prefix() . 'expenses', [
            'tax' => $data['taxid'],
        ]) > 0) {
            return [
                'tax_is_using_expenses' => true,
            ];
        }

        $updated      = false;
        $taxid        = $data['taxid'];
        $original_tax = get_tax_by_id($taxid);
        unset($data['taxid']);

        $data['name']    = trim($data['name']);
        $data['taxrate'] = trim($data['taxrate']);

        $data = hooks()->apply_filters('before_update_tax', $data, $taxid);

        $this->db->where('id', $taxid);
        $this->db->update('taxes', $data);

        if ($this->db->affected_rows() > 0) {
            // Check if this task is used in settings
            $default_taxes = unserialize(get_option('default_tax'));

            $i = 0;
            foreach ($default_taxes as $tax) {
                $current_tax = $this->get($taxid);
                $tax_name    = $original_tax->name . '|' . $original_tax->taxrate;
                if (strpos('x' . $tax, $tax_name) !== false) {
                    $default_taxes[$i] = str_ireplace($tax_name, $current_tax->name . '|' . $current_tax->taxrate, $default_taxes[$i]);
                }
                $i++;
            }

            update_option('default_tax', serialize($default_taxes));
            $updated = true;
        }

        hooks()->do_action('after_update_tax', [
            'id'      => $taxid,
            'data'    => $data,
            'updated' => &$updated,
        ]);

        if ($updated) {
            log_activity('Tax Updated [ID: ' . $taxid . ', ' . $data['name'] . ']');
        }

        return $updated;
    }

    /**
     * Delete tax from database
     * @param  mixed $id tax id
     * @return boolean
     */
    public function delete($id)
    {
        if (
            is_reference_in_table('tax', db_prefix() . 'items', $id)
            || is_reference_in_table('tax2', db_prefix() . 'items', $id)
            || is_reference_in_table('tax', db_prefix() . 'expenses', $id)
            || is_reference_in_table('tax2', db_prefix() . 'expenses', $id)
            || is_reference_in_table('tax_id', db_prefix() . 'subscriptions', $id)
            || is_reference_in_table('tax_id_2', db_prefix() . 'subscriptions', $id)
            ) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'taxes');
        if ($this->db->affected_rows() > 0) {
            log_activity('Tax Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }
}
