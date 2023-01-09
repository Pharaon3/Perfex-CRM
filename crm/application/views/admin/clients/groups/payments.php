<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
<h4 class="customer-profile-group-heading"><?php echo _l('client_payments_tab'); ?></h4>
<a href="#" class="btn btn-primary mbot15" data-toggle="modal" data-target="#client_zip_payments">
    <i class="fa-regular fa-file-zipper tw-mr-1"></i>
    <?php echo _l('zip_payments'); ?>
</a>
<?php
    $this->load->view('admin/payments/table_html', ['class' => 'payments-single-client']);
    $this->load->view('admin/clients/modals/zip_payments');
    ?>
<?php } ?>