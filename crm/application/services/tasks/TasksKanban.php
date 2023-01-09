<?php

namespace app\services\tasks;

use app\services\AbstractKanban;

class TasksKanban extends AbstractKanban
{
    protected function table()
    {
        return 'tasks';
    }

    public function defaultSortDirection()
    {
        return 'ASC';
    }

    public function defaultSortColumn()
    {
        return 'kanban_order';
    }

    public function limit()
    {
        return get_option('tasks_kanban_limit');
    }

    public function forProject($projectId)
    {
        return $this->tapQuery(function ($status, $ci) use ($projectId) {
            if ($projectId) {
                $ci->db->where('rel_type', 'project')
                    ->where('rel_id', $projectId);
            }
        });
    }

    protected function applySearchQuery($q)
    {
        if (!startsWith($q, '#')) {
            $q = $this->ci->db->escape_like_str($q);
            $this->ci->db->where('(' . db_prefix() . 'tasks.name LIKE "%' . $q . '%" ESCAPE \'!\'  OR ' . db_prefix() . 'tasks.description LIKE "%' . $q . '%" ESCAPE \'!\')');
        } else {
            $this->ci->db->where(db_prefix() . 'tasks.id IN
                (SELECT rel_id FROM ' . db_prefix() . 'taggables WHERE tag_id IN
                (SELECT id FROM ' . db_prefix() . 'tags WHERE name="' . $this->ci->db->escape_str(strafter($q, '#')) . '")
                AND ' . db_prefix() . 'taggables.rel_type=\'task\' GROUP BY rel_id HAVING COUNT(tag_id) = 1)
                ');
        }

        return $this;
    }

    protected function initiateQuery()
    {
        $where = '';

        if (!has_permission('tasks', '', 'view')) {
            $where = get_tasks_where_string(false);
        }

        $this->ci->db->select(tasks_rel_name_select_query() . ' as rel_name,rel_type,rel_id,id,priority,name,duedate,startdate,status,' . get_sql_select_task_total_checklist_items() . ',' . get_sql_select_task_total_finished_checklist_items() . ',(SELECT COUNT(id) FROM ' . db_prefix() . 'task_comments WHERE taskid=' . db_prefix() . 'tasks.id) as total_comments,(SELECT COUNT(id) FROM ' . db_prefix() . 'files WHERE rel_id=' . db_prefix() . 'tasks.id AND rel_type="task") as total_files,' . get_sql_select_task_asignees_full_names() . ' as assignees' . ',' . get_sql_select_task_assignees_ids() . ' as assignees_ids,(SELECT staffid FROM ' . db_prefix() . 'task_assigned WHERE taskid=' . db_prefix() . 'tasks.id AND staffid=' . get_staff_user_id() . ') as current_user_is_assigned, (SELECT CASE WHEN addedfrom=' . get_staff_user_id() . ' AND is_added_from_contact=0 THEN 1 ELSE 0 END) as current_user_is_creator');

        $this->ci->db->from('tasks');
        $this->ci->db->where('status', $this->status);

        if ($where != '') {
            $this->ci->db->where($where);
        }

        return $this;
    }
}
