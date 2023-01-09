<?php

namespace app\services;

defined('BASEPATH') or exit('No direct script access allowed');

class LeadProfileBadges
{
    protected $leadId;

    protected $CI;

    protected $staffId;

    public function __construct($leadId)
    {
        $this->leadId = $leadId;
        $this->staffId    = get_staff_user_id();
        $this->CI         = &get_instance();
    }

    public function getCount($feature)
    {
        if (method_exists($this, $feature)) {
            $count = $this->{$feature}();
        }

        $count = hooks()->apply_filters('lead_tab_badge_count', $count, [
            'feature' => $feature,
            'lead_id' => $this->leadId,
        ]);

        return $count;
    }

    /**
     * Get the total notes for the lead
     *
     * @return int
     */
    public function notes()
    {
        $this->CI->db->where('rel_type', 'lead');
        $this->CI->db->where('rel_id', $this->leadId);

        return $this->CI->db->count_all_results('notes');
    }

    /**
     * Get the total proposals for the lead staff has access to
     *
     * @return int
     */
    public function proposals()
    {
        if (!staff_can('view', 'proposals')) {
            $where = get_proposals_sql_where_staff($this->staffId);
            $this->CI->db->where($where);
        }
        $this->CI->db->where_in('status', [1, 4, 6]);
        $this->CI->db->where('rel_id', $this->leadId);
        $this->CI->db->where('rel_type', 'lead');

        return $this->CI->db->count_all_results('proposals');
    }

    /**
     * Get the total attachments for the lead
     *
     * @return int
     */
    public function attachments()
    {
        $this->CI->db->where('rel_id', $this->leadId);
        $this->CI->db->where('rel_type', 'lead');

        return $this->CI->db->count_all_results('files');
    }

    /**
     * Get the total reminders for the lead for staff.
     *
     * @return int
     */
    public function reminders()
    {
        $this->CI->db->where('rel_id', $this->leadId);
        $this->CI->db->where('rel_type', 'lead');
        $this->CI->db->where('staff', $this->staffId);
        $this->CI->db->where('isnotified', 0);

        return $this->CI->db->count_all_results('reminders');
    }

    public function tasks()
    {
        $this->CI->db->where('rel_id', $this->leadId);
        $this->CI->db->where('rel_type', 'lead');
        $this->CI->db->where('datefinished is NULL');

        if (!staff_can('view', 'tasks')) {
            $this->CI->db->where(get_tasks_where_string(false));
        }

        return $this->CI->db->count_all_results('tasks');
    }
}
