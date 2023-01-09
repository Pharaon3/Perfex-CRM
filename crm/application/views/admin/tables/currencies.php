<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'name',
    'symbol',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'currencies';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], [
    'id',
    'isdefault',
    'placement',
    'thousand_separator',
    'decimal_separator',
]);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $attributes = [
        'data-toggle'             => 'modal',
        'data-target'             => '#currency_modal',
        'data-id'                 => $aRow['id'],
        'data-placement'          => $aRow['placement'],
        'data-thousand-separator' => $aRow['thousand_separator'],
        'data-decimal-separator'  => $aRow['decimal_separator'],
        ];

        if ($aColumns[$i] == 'name') {
            $_data = '<span class="name"><a href="#" ' . _attributes_to_string($attributes) . '>' . $_data . '</a></span>';
            if ($aRow['isdefault'] == 1) {
                $_data .= '<span class="display-block text-info">' . _l('base_currency_string') . '</span>';
            }
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#' . $aRow['id'] . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" ' . _attributes_to_string($attributes) . '>
        <i class="fa-regular fa-pen-to-square fa-lg"></i>
    </a>';

    if ($aRow['isdefault'] == 0) {
        $options .= '<a href="' . admin_url('currencies/make_base_currency/' . $aRow['id']) . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" ' . _attributes_to_string([
            'data-toggle' => 'tooltip',
            'title'       => _l('make_base_currency'),
            ]) . '>
        <i class="fa-regular fa-star fa-lg"></i>
    </a>';
    }

    $options .= '<a href="' . admin_url('currencies/delete/' . $aRow['id']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';

    $options .= '</div>';

    $row[]              = $options;
    $output['aaData'][] = $row;
}