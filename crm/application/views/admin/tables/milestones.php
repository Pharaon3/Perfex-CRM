<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'start_date',
    'due_date',
    'description',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'milestones';

$where = [
    'AND project_id=' . $this->ci->db->escape_str($project_id),
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, [
    'id',
    'milestone_order',
    'description',
    'description_visible_to_customer',
    'hide_from_customer',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $nameRow = $aRow['name'];

    if (staff_can('edit_milestones', 'projects')) {
        $nameRow = '<a href="#" onclick="edit_milestone(this,' . $aRow['id'] . '); return false" data-name="' . $aRow['name'] . '" data-start_date="' . _d($aRow['start_date']) . '" data-due_date="' . _d($aRow['due_date']) . '" data-order="' . $aRow['milestone_order'] . '" data-description="' . htmlspecialchars(clear_textarea_breaks($aRow['description'])) . '" data-description-visible-to-customer="' . $aRow['description_visible_to_customer'] . '" data-hide-from-customer="' . $aRow['hide_from_customer'] . '">' . $nameRow . '</a>';
    }

    if (staff_can('delete_milestones', 'projects')) {
        $nameRow .= '<div class="row-options">';
        $nameRow .= '<a href="' . admin_url('projects/delete_milestone/' . $project_id . '/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
        $nameRow .= '</div>';
    }

    $row[] = $nameRow;
    $row[] =  _d($aRow['start_date']);

    $dateRow = _d($aRow['due_date']);

    if (date('Y-m-d') > $aRow['due_date'] && total_rows(db_prefix() . 'tasks', [
                'milestone' => $aRow['id'],
                'status !=' => 5,
                'rel_id' => $project_id,
                'rel_type' => 'project',
                ]) > 0) {
        $dateRow .= ' <span class="label label-danger mleft5 inline-block">' . _l('project_milestone_duedate_passed') . '</span>';
    }

    $row[] = $dateRow;

    $row[] = clear_textarea_breaks($aRow['description']);

    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
