<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <?php echo $title; ?>
                    </h4>

                    <?php if (isset($predefined_reply)) { ?>
                    <a href="<?php echo admin_url('tickets/predefined_reply'); ?>"
                        class="btn btn-primary"><?php echo _l('new_predefined_reply'); ?></a>
                    <?php } ?>
                </div>
                <div class="panel_s">
                    <div class="panel-body">

                        <?php echo form_open($this->uri->uri_string()); ?>

                        <?php $value = (isset($predefined_reply) ? $predefined_reply->name : ''); ?>
                        <?php $attrs = (isset($predefined_reply) ? [] : ['autofocus' => true]); ?>
                        <?php echo render_input('name', 'predefined_reply_add_edit_name', $value, 'text', $attrs); ?>
                        <?php $contents = ''; if (isset($predefined_reply)) {
    $contents                           = $predefined_reply->message;
} ?>
                        <?php echo render_textarea('message', '', $contents, [], [], '', 'tinymce'); ?>
                        <button type="submit" class="btn btn-primary pull-right"><?php echo _l('submit'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
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