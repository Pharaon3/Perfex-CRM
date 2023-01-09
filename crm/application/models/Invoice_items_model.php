<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_items_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Copy invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function copy($_data)
    {
        $custom_fields_items = get_custom_fields('items');

        $data = [
            'description'      => $_data['description'] . ' - Copy',
            'rate'             => $_data['rate'],
            'tax'              => $_data['taxid'],
            'tax2'             => $_data['taxid_2'],
            'group_id'         => $_data['group_id'],
            'unit'             => $_data['unit'],
            'long_description' => $_data['long_description'],
        ];

        foreach ($_data as $column => $value) {
            if (strpos($column, 'rate_currency_') !== false) {
                $data[$column] = $value;
            }
        }

        $columns = $this->db->list_fields(db_prefix() . 'items');
        $this->load->dbforge();
        foreach ($data as $column) {
            if (!in_array($column, $columns) && strpos($column, 'rate_currency_') !== false) {
                $field = [
                    $column => [
                        'type' => 'decimal(15,' . get_decimal_places() . ')',
                        'null' => true,
                    ],
                ];
                $this->dbforge->add_column('items', $field);
            }
        }

        foreach ($custom_fields_items as $cf) {
            $data['custom_fields']['items'][$cf['id']] = get_custom_field_value($_data['itemid'], $cf['id'], 'items_pr', false);
            if (!defined('COPY_CUSTOM_FIELDS_LIKE_HANDLE_POST')) {
                define('COPY_CUSTOM_FIELDS_LIKE_HANDLE_POST', true);
            }
        }

        $insert_id = $this->add($data);

        if ($insert_id) {
            hooks()->do_action('item_coppied', $insert_id);

            log_activity('Copied Item  [ID:' . $_data['itemid'] . ', ' . $data['description'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
    public function get($id = '')
    {
        $columns             = $this->db->list_fields(db_prefix() . 'items');
        $rateCurrencyColumns = '';
        foreach ($columns as $column) {
            if (strpos($column, 'rate_currency_') !== false) {
                $rateCurrencyColumns .= $column . ',';
            }
        }
        $this->db->select($rateCurrencyColumns . '' . db_prefix() . 'items.id as itemid,rate,
            t1.taxrate as taxrate,t1.id as taxid,t1.name as taxname,
            t2.taxrate as taxrate_2,t2.id as taxid_2,t2.name as taxname_2,
            description,long_description,group_id,' . db_prefix() . 'items_groups.name as group_name,unit');
        $this->db->from(db_prefix() . 'items');
        $this->db->join('' . db_prefix() . 'taxes t1', 't1.id = ' . db_prefix() . 'items.tax', 'left');
        $this->db->join('' . db_prefix() . 'taxes t2', 't2.id = ' . db_prefix() . 'items.tax2', 'left');
        $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
        $this->db->order_by('description', 'asc');
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'items.id', $id);

            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function get_grouped()
    {
        $items = [];
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
            $this->db->where('group_id', $group['id']);
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }

    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data['itemid']);
        if (isset($data['tax']) && $data['tax'] == '') {
            unset($data['tax']);
        }

        if (isset($data['tax2']) && $data['tax2'] == '') {
            unset($data['tax2']);
        }

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        $columns = $this->db->list_fields(db_prefix() . 'items');

        $this->load->dbforge();

        foreach ($data as $column => $itemData) {
            if (!in_array($column, $columns) && strpos($column, 'rate_currency_') !== false) {
                $field = [
                    $column => [
                        'type' => 'decimal(15,' . get_decimal_places() . ')',
                        'null' => true,
                    ],
                ];
                $this->dbforge->add_column('items', $field);
            }
        }

        $data          = hooks()->apply_filters('before_item_created', $data);
        $custom_fields = Arr::pull($data, 'custom_fields') ?? [];

        $this->db->insert('items', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            handle_custom_fields_post($insert_id, $custom_fields, true);

            hooks()->do_action('item_created', $insert_id);

            log_activity('New Invoice Item Added [ID:' . $insert_id . ', ' . $data['description'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update invoiec item
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data)
    {
        $itemid = $data['itemid'];
        unset($data['itemid']);

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        if (isset($data['tax']) && $data['tax'] == '') {
            $data['tax'] = null;
        }

        if (isset($data['tax2']) && $data['tax2'] == '') {
            $data['tax2'] = null;
        }

        $columns = $this->db->list_fields(db_prefix() . 'items');
        $this->load->dbforge();

        foreach ($data as $column => $itemData) {
            if (!in_array($column, $columns) && strpos($column, 'rate_currency_') !== false) {
                $field = [
                    $column => [
                        'type' => 'decimal(15,' . get_decimal_places() . ')',
                        'null' => true,
                    ],
                ];
                $this->dbforge->add_column('items', $field);
            }
        }

        $updated       = false;
        $data          = hooks()->apply_filters('before_update_item', $data, $itemid);
        $custom_fields = Arr::pull($data, 'custom_fields') ?? [];

        $this->db->where('id', $itemid);
        $this->db->update('items', $data);

        if ($this->db->affected_rows() > 0) {
            $updated = true;
        }

        if (handle_custom_fields_post($itemid, $custom_fields, true)) {
            $updated = true;
        }

        do_action_deprecated('item_updated', [$itemid], '2.9.4', 'after_item_updated');

        hooks()->do_action('after_item_updated', [
            'id'            => $itemid,
            'data'          => $data,
            'custom_fields' => $custom_fields,
            'updated'       => &$updated,
        ]);

        if ($updated) {
            log_activity('Invoice Item Updated [ID: ' . $itemid . ', ' . $data['description'] . ']');
        }

        return $updated;
    }

    public function search($q)
    {
        $this->db->select('rate, id, description as name, long_description as subtext');
        $this->db->like('description', $q);
        $this->db->or_like('long_description', $q);

        $items = $this->db->get(db_prefix() . 'items')->result_array();

        foreach ($items as $key => $item) {
            $items[$key]['subtext'] = strip_tags(mb_substr($item['subtext'], 0, 200)) . '...';
            $items[$key]['name']    = '(' . app_format_number($item['rate']) . ') ' . $item['name'];
        }

        return $items;
    }

    /**
     * Delete invoice item
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'items');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'items_pr');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            log_activity('Invoice Item Deleted [ID: ' . $id . ']');

            hooks()->do_action('item_deleted', $id);

            return true;
        }

        return false;
    }

    public function get_groups()
    {
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }

    public function add_group($data)
    {
        $this->db->insert(db_prefix() . 'items_groups', $data);
        log_activity('Items Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }

    public function edit_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_groups', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Items Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'items_groups')->row();

        if ($group) {
            $this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'items', [
                'group_id' => 0,
            ]);

            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'items_groups');

            log_activity('Item Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
}