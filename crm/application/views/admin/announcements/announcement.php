<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo $title; ?>
                </h4>
                <?php echo form_open($this->uri->uri_string()); ?>
                <div class="panel_s">
                    <div class="panel-body">


                        <?php $value = (isset($announcement) ? $announcement->name : ''); ?>
                        <?php echo render_input('name', 'announcement_name', $value); ?>

                        <p class="bold"><?php echo _l('announcement_message'); ?></p>
                        <?php $contents = ''; if (isset($announcement)) {
    $contents                           = $announcement->message;
} ?>
                        <?php echo render_textarea('message', '', $contents, [], [], '', 'tinymce'); ?>

                    </div>
                    <div class="panel-footer">
                        <div class="tw-flex tw-justify-between tw-items-center">
                            <div>

                                <div class="checkbox checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="showtostaff" id="showtostaff"
                                        <?php echo (!isset($announcement) || (isset($announcement) && $announcement->showtostaff == 1)) ? 'checked' : ''; ?>>
                                    <label for="showtostaff"><?php echo _l('announcement_show_to_staff'); ?></label>
                                </div>
                                <div class="checkbox checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="showtousers" id="showtousers"
                                        <?php echo isset($announcement) && $announcement->showtousers == 1 ? 'checked' : ''; ?>>
                                    <label for="showtousers"><?php echo _l('announcement_show_to_clients'); ?></label>
                                </div>
                                <div class="checkbox checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="showname" id="showname"
                                        <?php echo isset($announcement) && $announcement->showname == 1 ? 'checked' : ''; ?>>
                                    <label for="showname"><?php echo _l('announcement_show_my_name'); ?></label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                        </div>


                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    appValidateForm($('form'), {
        name: 'required'
    });
});
</script>
</body>

</html>