<?php

defined('BASEPATH') or exit('No direct script access allowed');

$baseCurrency = get_base_currency();
$project_id   = $this->ci->input->post('project_id');

$aColumns = [
    db_prefix() . 'proposals.id',
    'subject',
    'proposal_to',
    'total',
    'date',
    'open_till',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'proposals.id and rel_type="proposal" ORDER by tag_order ASC) as tags',
    'datecreated',
    db_prefix() . 'proposals.status as proposal_status',
];

if (!$project_id) {
    $aColumns[] = 'project_id';
}

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'proposals';

$where  = [];
$filter = [];

if ($this->ci->input->post('leads_related')) {
    array_push($filter, 'OR rel_type="lead"');
}
if ($this->ci->input->post('customers_related')) {
    array_push($filter, 'OR rel_type="customer"');
}
if ($this->ci->input->post('expired')) {
    array_push($filter, 'OR open_till IS NOT NULL AND open_till <"' . date('Y-m-d') . '" AND ' . db_prefix() . 'proposals.status NOT IN(2,3)');
}

$statuses  = $this->ci->proposals_model->get_statuses();
$statusIds = [];

foreach ($statuses as $status) {
    if ($this->ci->input->post('proposals_' . $status)) {
        array_push($statusIds, $status);
    }
}
if (count($statusIds) > 0) {
    array_push($filter, 'AND' . db_prefix() . 'proposals.status IN (' . implode(', ', $statusIds) . ')');
}

$agents    = $this->ci->proposals_model->get_sale_agents();
$agentsIds = [];
foreach ($agents as $agent) {
    if ($this->ci->input->post('sale_agent_' . $agent['sale_agent'])) {
        array_push($agentsIds, $agent['sale_agent']);
    }
}
if (count($agentsIds) > 0) {
    array_push($filter, 'AND assigned IN (' . implode(', ', $agentsIds) . ')');
}

$years      = $this->ci->proposals_model->get_proposals_years();
$yearsArray = [];
foreach ($years as $year) {
    if ($this->ci->input->post('year_' . $year['year'])) {
        array_push($yearsArray, $year['year']);
    }
}
if (count($yearsArray) > 0) {
    array_push($filter, 'AND YEAR(date) IN (' . implode(', ', $yearsArray) . ')');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

if (!has_permission('proposals', '', 'view')) {
    array_push($where, 'AND ' . get_proposals_sql_where_staff(get_staff_user_id()));
}

$join = [
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'proposals.project_id',
];
$custom_fields = get_table_custom_fields('proposal');

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'proposals.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

if ($project_id) {
    $where[] = 'AND project_id=' . $this->ci->db->escape_str($project_id);
}

$aColumns = hooks()->apply_filters('proposals_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'currency',
    'rel_id',
    'rel_type',
    'invoice_id',
    'hash',
    db_prefix() . 'projects.name as project_name',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $numberOutput = '<a href="' . admin_url('proposals/list_proposals/' . $aRow[db_prefix() . 'proposals.id']) . '"' . ($project_id ? 'target="_blank"' : 'onclick="init_proposal(' . $aRow[db_prefix() . 'proposals.id'] . '); return false;"') . '>' . format_proposal_number($aRow[db_prefix() . 'proposals.id']) . '</a>';

    $numberOutput .= '<div class="row-options">';

    $numberOutput .= '<a href="' . site_url('proposal/' . $aRow[db_prefix() . 'proposals.id'] . '/' . $aRow['hash']) . '" target="_blank">' . _l('view') . '</a>';
    if (has_permission('proposals', '', 'edit')) {
        $numberOutput .= ' | <a href="' . admin_url('proposals/proposal/' . $aRow[db_prefix() . 'proposals.id']) . '"' . ($project_id ? 'target="_blank"': '') . '>' . _l('edit') . '</a>';
    }
    $numberOutput .= '</div>';

    $row[] = $numberOutput;

    $row[] = '<a href="' . admin_url('proposals/list_proposals/' . $aRow[db_prefix() . 'proposals.id']) . '"' . ($project_id ? 'target="_blank"' : 'onclick="init_proposal(' . $aRow[db_prefix() . 'proposals.id'] . '); return false;"') . '>' . $aRow['subject'] . '</a>';

    if ($aRow['rel_type'] == 'lead') {
        $toOutput = '<a href="#" onclick="init_lead(' . $aRow['rel_id'] . ');return false;" target="_blank" data-toggle="tooltip" data-title="' . _l('lead') . '">' . $aRow['proposal_to'] . '</a>';
    } elseif ($aRow['rel_type'] == 'customer') {
        $toOutput = '<a href="' . admin_url('clients/client/' . $aRow['rel_id']) . '" target="_blank" data-toggle="tooltip" data-title="' . _l('client') . '">' . $aRow['proposal_to'] . '</a>';
    }

    $row[] = $toOutput;

    $amount = app_format_money($aRow['total'], ($aRow['currency'] != 0 ? get_currency($aRow['currency']) : $baseCurrency));

    if ($aRow['invoice_id']) {
        $amount .= '<br /> <span class="hide"> - </span><span class="text-success tw-text-sm">' . _l('estimate_invoiced') . '</span>';
    }

    $row[] = $amount;


    $row[] = _d($aRow['date']);

    $row[] = _d($aRow['open_till']);

    if (!$project_id) {
        $row[] = '<a href="' . admin_url('projects/view/' . $aRow['project_id']) . '" target="_blank">' . $aRow['project_name'] . '</a>';
    }

    $row[] = render_tags($aRow['tags']);

    $row[] = _d($aRow['datecreated']);

    $row[] = format_proposal_status($aRow['proposal_status']);

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('proposals_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}