<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="#" onclick="new_service(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_service'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([
                            _l('id'),
                            _l('services_dt_name'),
                            _l('options'),
                            ], 'services'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/tickets/services/service'); ?>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-services', window.location.href, [2], [2], 'undefined', [1, 'asc']);
});
</script>
</body>

</html>