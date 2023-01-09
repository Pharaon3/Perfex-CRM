<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_view_own = has_permission('estimate_request', '', 'view_own');
$has_permission_view     = has_permission('estimate_request', '', 'view');
$has_permission_edit     = has_permission('estimate_request', '', 'edit');
$has_permission_delete   = has_permission('estimate_request', '', 'delete');
$statuses                = $this->ci->estimate_request_model->get_status();

$aColumns = [
    db_prefix() . 'estimate_requests.id as id',
];
$aColumns = array_merge($aColumns, [
    db_prefix() . 'estimate_requests.email as email',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'estimate_requests.id and rel_type="estimate_request" ORDER by tag_order ASC LIMIT 1) as tags',
    'firstname as assigned_firstname',
    db_prefix() . 'estimate_request_status.name as status_name',
    'date_added',
]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'estimate_requests';

$join = [
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'estimate_requests.assigned',
    'LEFT JOIN ' . db_prefix() . 'estimate_request_status ON ' . db_prefix() . 'estimate_request_status.id = ' . db_prefix() . 'estimate_requests.status',
];

$where  = [];
$filter = false;


if (!$has_permission_view) {
    array_push($where, 'AND assigned =' . get_staff_user_id());
}

if (has_permission('estimate_request', '', 'view') && $this->ci->input->post('assigned')) {
    array_push($where, 'AND assigned =' . $this->ci->db->escape_str($this->ci->input->post('assigned')));
}

if (
    $this->ci->input->post('status')
    && count($this->ci->input->post('status')) > 0
    && ($filter != 'lost' && $filter != 'junk')
) {
    array_push($where, 'AND status IN (' . implode(',', $this->ci->db->escape_str($this->ci->input->post('status'))) . ')');
}


$aColumns = hooks()->apply_filters('estimate_request_table_sql_columns', $aColumns);

$additionalColumns = hooks()->apply_filters('estimate_request_table_additional_columns_sql', [
    'color',
    'status',
    'assigned',
    'lastname as assigned_lastname',
    '(SELECT count(leadid) FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.leadid=' . db_prefix() . 'estimate_requests.id) as is_converted',
]);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalColumns);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $hrefAttr = 'href="' . admin_url('estimate_request/view/' . $aRow['id']) . '"';
    $row[]    = '<a ' . $hrefAttr . '>' . $aRow['id'] . '</a>';

    $nameRow = '<a ' . $hrefAttr . '>' . $aRow['email'] . '</a>';

    $nameRow .= '<div class="row-options">';
    $nameRow .= '<a ' . $hrefAttr . '>' . _l('view') . '</a>';

    if ($has_permission_delete) {
        $nameRow .= ' | <a href="' . admin_url('estimate_request/delete/' . $aRow['id']) . '" class="_delete text-danger">' . _l('delete') . '</a>';
    }
    $nameRow .= '</div>';


    $row[] = $nameRow;

    $row[] .= render_tags($aRow['tags']);

    $assignedOutput = '';
    if ($aRow['assigned'] != 0) {
        $full_name = $aRow['assigned_firstname'] . ' ' . $aRow['assigned_lastname'];

        $assignedOutput = '<a data-toggle="tooltip" data-title="' . $full_name . '" href="' . admin_url('profile/' . $aRow['assigned']) . '">' . staff_profile_image($aRow['assigned'], [
            'staff-profile-image-small',
        ]) . '</a>';

        // For exporting
        $assignedOutput .= '<span class="hide">' . $full_name . '</span>';
    }

    $row[] = $assignedOutput;
    if (!$has_permission_edit) {
        $outputStatus = '<span class="label estimate_request-status-' . $aRow['status'] . '" style="color:' . $aRow['color'] . ';border:1px solid ' . adjust_hex_brightness($aRow['color'], 0.4) . ';background: ' . adjust_hex_brightness($aRow['color'], 0.04) . ';">' . $aRow['status_name'];

        $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
        $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableestimate_requestsStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa-solid fa-chevron-down"></i></span>';
        $outputStatus .= '</a>';

        $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableestimate_requestsStatus-' . $aRow['id'] . '">';
        foreach ($statuses as $_status) {
            if ($aRow['status'] != $_status['id']) {
                $outputStatus .= '<li>
                    <a href="#" onclick="mark_estimate_request_as(' . $_status['id'] . ',' . $aRow['id'] . '); return false;">
                        ' . $_status['name'] . '
                    </a>
                </li>';
            }
        }
        $outputStatus .= '</ul>';
        $outputStatus .= '</div>';
        $outputStatus .= '</span>';
    } else {
        $outputStatus = '<span class="label estimate_request-status-' . $aRow['status'] . '" style="color:' . $aRow['color'] . ';border:1px solid ' . adjust_hex_brightness($aRow['color'], 0.4) . ';background: ' . adjust_hex_brightness($aRow['color'], 0.04) . ';">' . $aRow['status_name'] . '</span>';
    }
    $row[] = $outputStatus;


    $row[] = '<span data-toggle="tooltip" data-title="' . _dt($aRow['date_added']) . '" class="text-has-action is-date">' . time_ago($aRow['date_added']) . '</span>';

    $row['DT_RowId'] = 'lead_' . $aRow['id'];

    if ($aRow['assigned'] == get_staff_user_id()) {
        $row['DT_RowClass'] = 'info';
    }

    if (isset($row['DT_RowClass'])) {
        $row['DT_RowClass'] .= ' has-row-options';
    } else {
        $row['DT_RowClass'] = 'has-row-options';
    }

    $row = hooks()->apply_filters('estimate_request_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}