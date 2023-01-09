<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = ['name'];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'customers_groups';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns) ; $i++) {
        $_data = '<a href="#" data-toggle="modal" data-target="#customer_group_modal" data-id="' . $aRow['id'] . '">' . $aRow[$aColumns[$i]] . '</a>';

        $row[] = $_data;
    }
    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-toggle="modal" data-target="#customer_group_modal" data-id="' . $aRow['id'] . '">
        <i class="fa-regular fa-pen-to-square fa-lg"></i>
    </a>';

    $options .= '<a href="' . admin_url('clients/delete_group/' . $aRow['id']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}