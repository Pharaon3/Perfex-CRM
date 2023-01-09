<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Notifications_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name' => 'Unbilled task list',
                'key' => '{unbilled_tasks_list}',
                'available' => [],
                'templates' => [
                    'non-billed-tasks-reminder',
                ],
            ],
        ];
    }

    /**
     * Merge fields for tasks
     * @return array
     */
    public function format()
    {
        $fields = [];

        $this->ci->db->where('billable', 1);
        $this->ci->db->where('billed', 0);
        $this->ci->db->where('status', Tasks_model::STATUS_COMPLETE);
        $nonBilledTasks = $this->ci->db->get(db_prefix() . 'tasks')->result_array();

        $non_billed_tasks_list = '<ol>';
        foreach ($nonBilledTasks as $task) {
            $non_billed_tasks_list .= '<li><a href="' . admin_url('tasks/view/' . $task['id']) . '">' . $task['name'] . '</a></li>';
        }
        $non_billed_tasks_list .= '</ol>';

        $fields['{unbilled_tasks_list}'] = $non_billed_tasks_list;

        return hooks()->apply_filters('notifications_merge_fields', $fields);
    }
}
