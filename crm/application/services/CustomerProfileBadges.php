<?php

namespace app\services;

defined('BASEPATH') or exit('No direct script access allowed');

class CustomerProfileBadges
{
    protected $customerId;

    protected $CI;

    protected $staffId;

    public function __construct($customerId)
    {
        $this->customerId = $customerId;
        $this->staffId    = get_staff_user_id();
        $this->CI         = &get_instance();
    }

    public function getBadge($feature)
    {
        $badge = [];

        if (method_exists($this, $feature)) {
            if ($count = $this->{$feature}()) {
                $badge = [
                    'value' => $count,
                    'color' => '',
                    'type'  => 'bg-default',
                ];
            }
        }

        $hook = hooks()->apply_filters('customers_profile_tab_badge', [
            'feature'     => $feature,
            'customer_id' => $this->customerId,
            'badge'       => $badge,
        ]);

        return $hook['badge'];
    }

    /**
     * Get the total contacts for the customer profile badge
     *
     * @return int
     */
    public function contacts()
    {
        $this->CI->db->where('userid', $this->customerId);

        return $this->CI->db->count_all_results('contacts');
    }

    /**
     * Get the total notes for the customer profile badge
     *
     * @return int
     */
    public function notes()
    {
        $this->CI->db->where('rel_type', 'customer');
        $this->CI->db->where('rel_id', $this->customerId);

        return $this->CI->db->count_all_results('notes');
    }

    public function invoices()
    {
        if (!class_exists('Invoices_model', false)) {
            $this->CI->load->model('invoices_model');
        }

        if (!staff_can('view', 'invoices')) {
            $where = get_invoices_where_sql_for_staff($this->staffId);
            $this->CI->db->where($where);
        }
        $this->CI->db->where_not_in('status', [\Invoices_model::STATUS_CANCELLED, \Invoices_model::STATUS_PAID]);
        $this->CI->db->where('clientid', $this->customerId);

        return $this->CI->db->count_all_results('invoices');
    }

    public function proposals()
    {
        if (!staff_can('view', 'proposals')) {
            $where = get_proposals_sql_where_staff($this->staffId);
            $this->CI->db->where($where);
        }
        $this->CI->db->where_in('status', [1, 4, 6]);
        $this->CI->db->where('rel_id', $this->customerId);
        $this->CI->db->where('rel_type', 'customer');

        return $this->CI->db->count_all_results('proposals');
    }

    public function subscriptions()
    {
        if (!staff_can('view', 'subscriptions')) {
            $this->CI->db->where('(' . db_prefix() . 'subscriptions.created_from=' . $this->staffId . ' AND ' . db_prefix() . 'subscriptions.created_from IN (SELECT staff_id FROM ' . db_prefix() . 'staff_permissions WHERE feature = "subscriptions" AND capability="view_own"))');
        }

        $this->CI->db->group_start();
        $this->CI->db->where_in('status', ['past_due', 'unpaid', 'incomplete']);
        // Not subscribed
        $this->CI->db->where('stripe_subscription_id IS NULL OR stripe_subscription_id = ""');
        $this->CI->db->group_end();
        $this->CI->db->where('clientid ', $this->customerId);

        return $this->CI->db->count_all_results('subscriptions');
    }

    public function estimates()
    {
        if (!staff_can('view', 'estimates')) {
            $where = get_estimates_where_sql_for_staff($this->staffId);
            $this->CI->db->where($where);
        }

        $this->CI->db->group_start();
        $this->CI->db->where_in('status', [1, 2]);
        $this->CI->db->or_where('status IN (1,2) AND sent=0');
        $this->CI->db->group_end();
        $this->CI->db->where('clientid', $this->customerId);

        return $this->CI->db->count_all_results('estimates');
    }

    public function credit_notes()
    {
        if (!staff_can('view', 'credit_notes')) {
            $this->CI->db->where('(' . db_prefix() . 'creditnotes.addedfrom=' . $this->staffId . ' AND ' . db_prefix() . 'creditnotes.addedfrom IN (SELECT staff_id FROM ' . db_prefix() . 'staff_permissions WHERE feature = "credit_notes" AND capability="view_own"))');
        }

        $this->CI->db->where('clientid', $this->customerId);
        $this->CI->db->where('status', 1);

        return $this->CI->db->count_all_results('creditnotes');
    }

    public function contracts()
    {
        if (!staff_can('view', 'contracts')) {
            $this->CI->db->where('(' . db_prefix() . 'contracts.addedfrom=' . $this->staffId . ' AND ' . db_prefix() . 'contracts.addedfrom IN (SELECT staff_id FROM ' . db_prefix() . 'staff_permissions WHERE feature = "contracts" AND capability="view_own"))');
        }

        $this->CI->db->where('client', $this->customerId);
        $this->CI->db->where('signed', 0);

        return $this->CI->db->count_all_results('contracts');
    }

    public function projects()
    {
        if (!staff_can('view', 'projects')) {
            $this->CI->db->where(db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . $this->staffId . ')');
        }

        $this->CI->db->where('status', 2);
        $this->CI->db->where('clientid', $this->customerId);

        return $this->CI->db->count_all_results('projects');
    }

    public function tickets()
    {
        if (!is_admin()) {
            $departments_ids      = [];
            if (get_option('staff_access_only_assigned_departments') == 1) {
                $this->CI->load->model('departments_model');
                $staff_deparments_ids = $this->CI->departments_model->get_staff_departments($this->staffId, true);
                if (count($staff_deparments_ids) == 0) {
                    $departments = $this->CI->departments_model->get();
                    foreach ($departments as $department) {
                        array_push($departments_ids, $department['departmentid']);
                    }
                } else {
                    $departments_ids = $staff_deparments_ids;
                }

                if (count($departments_ids) > 0) {
                    $this->CI->db->group_start();
                    $this->CI->db->where('department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . $this->staffId . '")');
                }
            }
            $this->CI->db->or_where('assigned', $this->staffId);
            if (count($departments_ids) > 0) {
                $this->CI->db->group_end();
            }
        }
        $this->CI->db->where('userid', $this->customerId);

        return $this->CI->db->count_all_results('tickets');
    }

    public function attachments()
    {
        $this->CI->db->where('rel_id', $this->customerId);
        $this->CI->db->where('rel_type', 'customer');

        return $this->CI->db->count_all_results('files');
    }

    public function vault()
    {
        $this->CI->db->where('customer_id', $this->customerId);

        return $this->CI->db->count_all_results('vault');
    }

    public function expenses()
    {
        $this->CI->db->where('clientid', $this->customerId);
        $this->CI->db->where('billable', 1);
        $this->CI->db->where('invoiceid IS NULL');

        return $this->CI->db->count_all_results('expenses');
    }

    public function reminders()
    {
        $this->CI->db->where('rel_id', $this->customerId);
        $this->CI->db->where('rel_type', 'customer');
        $this->CI->db->where('isnotified', 0);

        return $this->CI->db->count_all_results('reminders');
    }

    public function tasks()
    {
        $where = '(';
        $where .= '(rel_id IN (SELECT id FROM ' . db_prefix() . 'invoices WHERE clientid=' . $this->customerId . ') AND rel_type="invoice")';
        $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'estimates WHERE clientid=' . $this->customerId . ') AND rel_type="estimate")';
        $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'contracts WHERE client=' . $this->customerId . ') AND rel_type="contract")';
        $where .= ' OR (rel_id IN (SELECT ticketid FROM ' . db_prefix() . 'tickets WHERE userid=' . $this->customerId . ') AND rel_type="ticket")';
        $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'expenses WHERE clientid=' . $this->customerId . ') AND rel_type="expense")';
        $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'proposals WHERE rel_id=' . $this->customerId . ' AND rel_type="proposal") AND rel_type="proposal")';
        $where .= ' OR (rel_id IN (SELECT userid FROM ' . db_prefix() . 'clients WHERE userid=' . $this->customerId . ') AND rel_type="customer")';
        $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'projects WHERE clientid=' . $this->customerId . ') AND rel_type="project")';
        $where .= ')';

        $this->CI->db->where($where);
        $this->CI->db->where('datefinished is NULL');

        if (!staff_can('view', 'tasks')) {
            $this->CI->db->where(get_tasks_where_string(false));
        }

        return $this->CI->db->count_all_results('tasks');
    }
}
