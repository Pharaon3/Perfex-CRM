<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'name',
    'taxrate',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'taxes';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data                       = $aRow[$aColumns[$i]];
        $is_referenced_expenses      = (total_rows(db_prefix() . 'expenses', ['tax' => $aRow['id']]) > 0 || total_rows(db_prefix() . 'expenses', ['tax2' => $aRow['id']]) > 0 ? 1 : 0);
        $is_referenced_subscriptions = total_rows(db_prefix() . 'subscriptions', ['tax_id' => $aRow['id']]) > 0 || total_rows(db_prefix() . 'subscriptions', ['tax_id_2' => $aRow['id']]) > 0;
        if ($aColumns[$i] == 'name') {
            $_data = '<a href="#" data-toggle="modal" data-is-referenced-expenses="' . $is_referenced_expenses . '" data-is-referenced-subscriptions="' . $is_referenced_subscriptions . '" data-target="#tax_modal" data-id="' . $aRow['id'] . '">' . $_data . '</a>';
        }
        $row[] = $_data;
    }
    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#' . $aRow['id'] . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" ' . _attributes_to_string([
        'data-toggle'                      => 'modal',
        'data-target'                      => '#tax_modal',
        'data-id'                          => $aRow['id'],
        'data-is-referenced-expenses'      => $is_referenced_expenses,
        'data-is-referenced-subscriptions' => $is_referenced_subscriptions,
        ]) . '>
        <i class="fa-regular fa-pen-to-square fa-lg"></i>
    </a>';

    $options .= '<a href="' . admin_url('taxes/delete/' . $aRow['id']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}