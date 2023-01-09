<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#payment_mode_modal">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_payment_mode'); ?>
                    </a>
                </div>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <p class="text-warning"><?php echo _l('payment_modes_add_edit_announcement'); ?></p>
                        <?php render_datatable([
                            _l('id'),
                            _l('payment_modes_dt_name'),
                            _l('payment_modes_dt_description'),
                            _l('payment_modes_dt_active'),
                            _l('options'),
                            ], 'payment-modes'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/paymentmodes/paymentmode'); ?>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-payment-modes', window.location.href, [4], [4], 'undefined', [1, 'asc']);
});
</script>
</body>

</html>