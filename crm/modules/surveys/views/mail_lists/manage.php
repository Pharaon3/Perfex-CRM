<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('surveys', '', 'create')) { ?>
                <div class="_buttons tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('surveys/mail_list'); ?>"
                        class="btn btn-primary pull-left display-block">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_mail_list'); ?>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body">

                        <?php render_datatable([
                            _l('id'),
                            _l('mail_lists_dt_list_name'),
                            _l('mail_lists_dt_datecreated'),
                            _l('mail_lists_dt_creator'),
                            _l('options'),
                            ], 'mail-lists'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-mail-lists', window.location.href, [4], [4]);
});
</script>
</body>

</html>