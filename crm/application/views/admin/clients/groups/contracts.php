<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
<h4 class="customer-profile-group-heading"><?php echo _l('contracts_invoices_tab'); ?></h4>
<?php if (has_permission('contracts', '', 'create')) { ?>
<a href="<?php echo admin_url('contracts/contract?customer_id=' . $client->userid); ?>"
    class="btn btn-primary mbot15<?php echo $client->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?php echo _l('new_contract'); ?>
</a>
<div class="clearfix"></div>
<?php } ?>
<?php $this->load->view('admin/contracts/table_html', ['class' => 'contracts-single-client']); ?>
<?php } ?>