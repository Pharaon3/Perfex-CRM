<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table_data = [
 _l('the_number_sign'),
 _l('contract_list_subject'),
 [
   'name'     => _l('contract_list_client'),
   'th_attrs' => ['class' => (isset($client) ? 'not_visible' : '')],
 ],
 _l('contract_types_list_name'),
 _l('contract_value'),
 _l('contract_list_start_date'),
 _l('contract_list_end_date'),
 (!isset($project) ? _l('project') : [
   'name'     => _l('project'),
   'th_attrs' => ['class' => 'not_visible'],
 ]),
 _l('signature'),
];
$custom_fields = get_custom_fields('contracts', ['show_on_table' => 1]);

foreach ($custom_fields as $field) {
    array_push($table_data, [
   'name'     => $field['name'],
   'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
 ]);
}

$table_data = hooks()->apply_filters('contracts_table_columns', $table_data);

render_datatable($table_data, (isset($class) ? $class : 'contracts'), ['number-index-1'], [
  'data-last-order-identifier' => 'contracts',
  'data-default-order'         => get_table_last_order('contracts'),
]);