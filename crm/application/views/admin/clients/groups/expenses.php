<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
<h4 class="customer-profile-group-heading"><?php echo _l('client_expenses_tab'); ?></h4>
<?php if (has_permission('expenses', '', 'create')) { ?>
<a href="<?php echo admin_url('expenses/expense?customer_id=' . $client->userid); ?>"
    class="btn btn-primary mbot15<?php echo $client->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?php echo _l('new_expense'); ?>
</a>
<?php } ?>
<div id="expenses_total" class="tw-mb-5"></div>
<?php $this->load->view('admin/expenses/table_html', [
    'class'           => 'expenses-single-client',
    'withBulkActions' => false,
]); ?>
<?php } ?>