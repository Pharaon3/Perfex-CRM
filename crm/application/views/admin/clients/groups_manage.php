<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#customer_group_modal">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_customer_group'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([
                        _l('customer_group_name'),
                        _l('options'),
                        ], 'customer-groups'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/clients/client_group'); ?>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-customer-groups', window.location.href, [1], [1]);
});
</script>
</body>

</html>