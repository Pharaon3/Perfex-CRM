<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('tickets/predefined_reply'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_predefined_reply'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([
                            _l('predefined_replies_dt_name'),
                            _l('options'),
                            ], 'predefined-replies'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-predefined-replies', window.location.href, [1], [1]);
});
</script>
</body>

</html>