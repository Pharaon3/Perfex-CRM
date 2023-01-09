<?php

namespace app\services\projects;

class AllProjectsGantt extends AbstractGantt
{
    protected $ci;

    protected $filters = [];

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->ci      = &get_instance();
    }

    public function get()
    {
        $gantt = [];

        foreach ($this->ci->projects_model->get_project_statuses() as $status) {
            if (!in_array($status['id'], $this->filters['status'])) {
                continue;
            }

            $projects = $this->queryProjectsForStatus($status['id']);

            foreach ($projects as $project) {
                $row     = $this->prepareGanttRow($project);
                $gantt[] = $row;
                $tasks   = $this->ci->projects_model->get_tasks($project['id'], [], true);

                foreach ($tasks as $task) {
                    $gantt[] = array_merge(static::tasks_array_data($task, null, isset($row['end']) ? $row['end'] : null), [
                        'progress'     => 0,
                        'dependencies' => $row['id'],
                    ]);
                }
            }
        }

        return $gantt;
    }

    protected function queryProjectsForStatus($id)
    {
        if (!has_permission('projects', '', 'view')) {
            $this->ci->db->where(db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')');
        }

        if ($this->filters['member']) {
            $this->ci->db->where(db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . $this->ci->db->escape_str($this->filters['member']) . ')');
        }

        $this->ci->db->where('status', $id);
        $this->ci->db->order_by('deadline IS NULL ASC, deadline', '', false);

        return $this->ci->db->get('projects')->result_array();
    }

    protected function prepareGanttRow($project)
    {
        $row               = [];
        $row['id']         = 'proj_' . $project['id'];
        $row['project_id'] = $project['id'];
        $row['name']       = $project['name'];
        $row['progress']   = 0;
        $row['start']      = date('Y-m-d', strtotime($project['start_date']));

        if (!empty($project['deadline'])) {
            $row['end'] = date('Y-m-d', strtotime($project['deadline']));
        }

        $row['custom_class'] = 'noDrag';

        return $row;
    }
}
