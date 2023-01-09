<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'name',
    'description',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'expenses_categories';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], []);
$output       = $result['output'];
$rResult      = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name' || $aColumns[$i] == 'id') {
            $_data = '<a href="#" onclick="edit_category(this,' . $aRow['id'] . '); return false;" data-name="' . $aRow['name'] . '" data-description="' . clear_textarea_breaks($aRow['description']) . '">' . $_data . '</a>';
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" onclick="edit_category(this,' . $aRow['id'] . '); return false;" data-name="' . $aRow['name'] . '" data-description="' . clear_textarea_breaks($aRow['description']) . '">
        <i class="fa-regular fa-pen-to-square fa-lg"></i>
    </a>';

    $options .= '<a href="' . admin_url('expenses/delete_category/' . $aRow['id']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';
    $options .= '</div>';

    $row[]              = $options;
    $output['aaData'][] = $row;
}