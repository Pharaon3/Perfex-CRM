<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
<h4 class="customer-profile-group-heading"><?php echo _l('estimates'); ?></h4>

<?php if (has_permission('estimates', '', 'create')) { ?>
<a href="<?php echo admin_url('estimates/estimate?customer_id=' . $client->userid); ?>"
    class="btn btn-primary mbot15<?php echo $client->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?php echo _l('create_new_estimate'); ?>
</a>
<?php } ?>
<?php if (has_permission('estimates', '', 'view') || has_permission('estimates', '', 'view_own') || get_option('allow_staff_view_estimates_assigned') == '1') { ?>
<a href="#" class="btn btn-primary mbot15" data-toggle="modal" data-target="#client_zip_estimates">
    <i class="fa-regular fa-file-zipper tw-mr-1"></i>
    <?php echo _l('zip_estimates'); ?>
</a>
<?php } ?>
<div id="estimates_total" class="tw-mb-5"></div>
<?php
    $this->load->view('admin/estimates/table_html', ['class' => 'estimates-single-client']);
    $this->load->view('admin/clients/modals/zip_estimates');
?>
<?php } ?>