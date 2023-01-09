<?php

namespace app\services\projects;

abstract class AbstractGantt
{
    abstract public function get();

    public static function tasks_array_data($task, $dep_id = null, $defaultEnd = null)
    {
        $data = [];

        $data['id']     = $task['id'];
        $data['desc']   = $task['name'];
        $data['status'] = $task['status'];

        $data['start'] = date('Y-m-d', strtotime($task['startdate']));

        if ($task['duedate']) {
            $data['end'] = date('Y-m-d', strtotime($task['duedate']));
        } else {
            $data['end'] = $defaultEnd;
        }

        $data['desc']  = $task['name'] . ' - ' . _l('task_total_logged_time') . ' ' . seconds_to_time_format($task['total_logged_time']);
        $data['label'] = $task['name'];
        if ($task['duedate'] && date('Y-m-d') > $task['duedate'] && $task['status'] != \Tasks_model::STATUS_COMPLETE) {
            $data['custom_class'] = 'ganttRed';
        } elseif ($task['status'] == \Tasks_model::STATUS_COMPLETE) {
            $data['custom_class'] = 'ganttGreen';
        }

        $data['name']     = $task['name'];
        $data['task_id']  = $task['id'];
        $data['progress'] = 0;

        //for task in single project gantt
        if ($dep_id) {
            $data['dependencies'] = $dep_id;
        }

        if (!staff_can('edit', 'tasks') || is_client_logged_in()) {
            if (isset($data['custom_class'])) {
                $data['custom_class'] .= ' noDrag';
            } else {
                $data['custom_class'] = 'noDrag';
            }
        }

        return $data;
    }
}
