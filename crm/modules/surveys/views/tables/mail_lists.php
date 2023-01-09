<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'listid',
    'name',
    db_prefix() . 'emaillists.datecreated',
    'creator',
    ];

$sIndexColumn = 'listid';
$sTable       = db_prefix() . 'emaillists';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], []);
$output       = $result['output'];
$rResult      = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name') {
            $_data = '<a href="' . admin_url('surveys/mail_list_view/' . $aRow['listid']) . '">' . $_data . '</a>';
            $_data .= '<p>Total emails: ' . total_rows(db_prefix() . 'listemails', 'listid=' . $aRow['listid']) . '</p>';
        } elseif ($aColumns[$i] == db_prefix() . 'emaillists.datecreated') {
            $_data = _dt($_data);
        }
        $row[] = $_data;
    }
    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="' . admin_url('surveys/mail_list_view/' . $aRow['listid']) . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
        <i class="fa fa-eye fa-lg"></i>
    </a>';

    if (has_permission('surveys', '', 'edit')) {
        $options .= '<a href="' . admin_url('surveys/mail_list/' . $aRow['listid']) . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
            <i class="fa-regular fa-pen-to-square fa-lg"></i>
        </a>';
    }

    if (has_permission('surveys', '', 'delete')) {
        $options .= '<a href="' . admin_url('surveys/delete_mail_list/' . $aRow['listid']) . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
            <i class="fa-regular fa-trash-can fa-lg"></i>
        </a>';
    }

    $options .= '</div>';
    $row[] = $options;

    $output['aaData'][] = $row;
}
$staff_mail_list_row = [
    '--',
    '<a href="' . site_url('admin/surveys/mail_list_view/staff') . '" data-toggle="tooltip" title="' . _l('cant_edit_mail_list') . '">' . _l('survey_send_mail_list_staff') . '</a>',
    '--',
    '--',
    '<a href="' . site_url('admin/surveys/mail_list_view/staff') . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700"><i class="fa fa-eye fa-lg"></i>',
    ];
$clients_mail_list_row = [
    '--',
    '<a href="' . site_url('admin/surveys/mail_list_view/clients') . '" data-toggle="tooltip" title="' . _l('cant_edit_mail_list') . '">' . _l('customer_contacts') . '</a>',
    '--',
    '--',
    '<a href="' . site_url('admin/surveys/mail_list_view/clients') . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700"><i class="fa fa-eye fa-lg"></i>',
    ];
$leads_mail_list_row = [
    '--',
    '<a href="' . site_url('admin/surveys/mail_list_view/leads') . '" data-toggle="tooltip" title="' . _l('cant_edit_mail_list') . '">' . _l('leads') . '</a>',
    '--',
    '--',
    '<a href="' . site_url('admin/surveys/mail_list_view/leads') . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700"><i class="fa fa-eye fa-lg"></i>',
    ];
// Add clients and staff mail lists to top always
array_unshift($output['aaData'], $staff_mail_list_row);
array_unshift($output['aaData'], $clients_mail_list_row);
array_unshift($output['aaData'], $leads_mail_list_row);