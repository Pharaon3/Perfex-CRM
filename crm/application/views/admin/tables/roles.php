<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    ];

$sIndexColumn = 'roleid';
$sTable       = db_prefix() . 'roles';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['roleid']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name') {
            $_data = '<a href="' . admin_url('roles/role/' . $aRow['roleid']) . '" class="mbot10 display-block">' . $_data . '</a>';
            $_data .= '<span class="mtop10 display-block">' . _l('roles_total_users') . ' ' . total_rows(db_prefix() . 'staff', [
                'role' => $aRow['roleid'],
                ]) . '</span>';
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="' . admin_url('roles/role/' . $aRow['roleid']) . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
        <i class="fa-regular fa-pen-to-square fa-lg"></i>
    </a>';

    $options .= '<a href="' . admin_url('roles/delete/' . $aRow['roleid']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}