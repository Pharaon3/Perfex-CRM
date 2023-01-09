<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$hasPermission = staff_can('edit', 'expenses') || staff_can('edit', 'expenses');
if ($withBulkActions === true && $hasPermission) { ?>
<a href="#" data-toggle="modal" data-target="#expenses_bulk_actions" class="hide bulk-actions-btn table-btn"
    data-table=".table-expenses"><?php echo _l('bulk_actions'); ?></a>
<?php } ?>
<?php
$table_data = [
  [
    'name'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="expenses"><label></label></div>',
    'th_attrs' => ['class' => $withBulkActions === true && $hasPermission ? '' : 'not_visible'],
  ],
  _l('the_number_sign'),
  _l('expense_dt_table_heading_category'),
  _l('expense_dt_table_heading_amount'),
  _l('expense_name'),
  _l('receipt'),
  _l('expense_dt_table_heading_date'),
];

if (!isset($project)) {
    array_push($table_data, _l('project'));
    array_push($table_data, [
    'name'     => _l('expense_dt_table_heading_customer'),
    'th_attrs' => ['class' => (isset($client) ? 'not_visible' : '')],
  ]);
} else {
    array_shift($table_data);
}

$table_data = array_merge($table_data, [
  _l('invoice'),
  _l('expense_dt_table_heading_reference_no'),
  _l('expense_dt_table_heading_payment_mode'),
]);

$custom_fields = get_custom_fields('expenses', ['show_on_table' => 1]);

foreach ($custom_fields as $field) {
    array_push($table_data, [
   'name'     => $field['name'],
   'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
 ]);
}

$table_data = hooks()->apply_filters('expenses_table_columns', $table_data);
render_datatable($table_data, (isset($class) ? $class : 'expenses'), [], [
  'data-last-order-identifier' => 'expenses',
  'data-default-order'         => get_table_last_order('expenses'),
]);

echo $this->view('admin/expenses/_bulk_actions_modal');