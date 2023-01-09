<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'contracts_types';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name') {
            $_data = '<a href="#" onclick="edit_type(this,' . $aRow['id'] . '); return false;" data-name="' . $aRow['name'] . '">' . $_data . '</a> ' . '<span class="badge pull-right">' . total_rows(db_prefix() . 'contracts', ['contract_type' => $aRow['id']]) . '</span>';
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" onclick="edit_type(this,' . $aRow['id'] . '); return false;" data-name="' . $aRow['name'] . '">
        <i class="fa-regular fa-pen-to-square fa-lg"></i>
    </a>';

    $options .= '<a href="' . admin_url('contracts/delete_contract_type/' . $aRow['id']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}