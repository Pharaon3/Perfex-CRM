<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('estimate_request/form'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_form'); ?>
                    </a>
                </div>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">

                        <?php hooks()->do_action('forms_table_start'); ?>
                        <?php render_datatable([
              _l('id'),
              _l('form_name'),
              _l('total_submissions'),
              _l('estimate_request_dt_datecreated'),
            ], 'estimate-request-forms'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-estimate-request-forms', window.location.href);
});
</script>
</body>

</html>