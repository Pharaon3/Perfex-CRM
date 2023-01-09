<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'name',
    'description',
    'active',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'payment_modes';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], [
    'expenses_only',
    'invoices_only',
    'show_on_pdf',
    'selected_by_default',
    ]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        if ($aColumns[$i] == 'active') {
            $checked = '';
            if ($aRow['active'] == 1) {
                $checked = 'checked';
            }
            $_data = '<div class="onoffswitch">
                <input type="checkbox" data-switch-url="' . admin_url() . 'paymentmodes/change_payment_mode_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
            </div>';
            // For exporting
            $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
        } elseif ($aColumns[$i] == 'name' || $aColumns[$i] == 'id') {
            $_data = '<a href="#" data-toggle="modal" data-default-selected="' . $aRow['selected_by_default'] . '" data-show-on-pdf="' . $aRow['show_on_pdf'] . '" data-target="#payment_mode_modal" data-expenses-only="' . $aRow['expenses_only'] . '" data-invoices-only="' . $aRow['invoices_only'] . '" data-id="' . $aRow['id'] . '">' . $_data . '</a>';
        }

        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" ' . _attributes_to_string([
        'data-toggle'           => 'modal',
        'data-target'           => '#payment_mode_modal',
        'data-id'               => $aRow['id'],
        'data-expenses-only'    => $aRow['expenses_only'],
        'data-invoices-only'    => $aRow['invoices_only'],
        'data-show-on-pdf'      => $aRow['show_on_pdf'],
        'data-default-selected' => $aRow['selected_by_default'],
        ]) . '>
        <i class="fa-regular fa-pen-to-square fa-lg"></i>
    </a>';

    $options .= '<a href="' . admin_url('paymentmodes/delete/' . $aRow['id']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}