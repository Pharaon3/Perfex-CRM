<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('roles/role'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_role'); ?>
                    </a>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-table-full">
                            <?php render_datatable([
                            _l('roles_dt_name'),
                            _l('options'),
                            ], 'roles'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
initDataTable('.table-roles', window.location.href, [1], [1]);
</script>
</body>

</html>