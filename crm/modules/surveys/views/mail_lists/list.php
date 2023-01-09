<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="tw-flex tw-justify-between tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <?php echo $title; ?>
                    </h4>
                    <?php if (isset($list)) { ?>
                    <div>
                        <?php if (has_permission('surveys', '', 'create')) { ?>
                        <a href="<?php echo admin_url('surveys/mail_list'); ?>"
                            class="btn btn-success btn-sm pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_mail_list'); ?>
                        </a>
                        <?php } ?>
                        <a href="<?php echo admin_url('surveys/mail_lists'); ?>"
                            class="btn btn-default btn-sm mleft5 pull-left display-block">
                            <i class="fa-solid fa-envelopes-bulk tw-mr-1"></i>
                            <?php echo _l('mail_lists'); ?>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                    <?php } ?>
                </div>
                <?php echo form_open($this->uri->uri_string()); ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php $value = (isset($list) ? $list->name : ''); ?>
                        <?php echo render_input('name', 'mail_list_add_edit_name', $value); ?>
                        <div class="form-group">
                            <a href="#" class="btn btn-default add_list_custom_field"
                                onclick="add_list_custom_field()"><i class="fa fa-plus"></i>
                                <?php echo _l('mail_list_add_edit_customfield'); ?></a>
                        </div>
                        <div class="list_custom_fields_area">
                            <?php
                            if (isset($list)) {
                                if (count($custom_fields) > 0) {
                                    foreach ($custom_fields as $field) { ?>
                            <div class="input-group list_custom_field mbot15">
                                <input type="text"
                                    name="list_custom_fields_update[<?php echo $field['customfieldid']; ?>]"
                                    value="<?php echo $field['fieldname']; ?>" class="form-control">
                                <span class="input-group-addon">
                                    <a href="#"
                                        onclick="remove_list_custom_field(this,<?php echo $field['customfieldid']; ?>)"><i
                                            class="fa fa-remove"></i></a>
                                </span>
                            </div>
                            <?php }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
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
// Will add mail list custom field
function add_list_custom_field(listid) {

    var name = "list_custom_fields_add[]";
    if (typeof(listid) !== 'undefined') {
        name = 'list_custom_fields_update[' + listid + ']'
    }

    $('.list_custom_fields_area').append('<div class="input-group list_custom_field mbot15"><input type="text" name="' +
        name +
        '" placeholder="Enter field name" class="form-control"><span class="input-group-addon"><a href="#" onclick="remove_list_custom_field(this)"><i class="fa fa-remove"></i></a></span></div>'
    )
}
// Remove mail list custom field / if is edit removes from database
function remove_list_custom_field(field, fieldid) {
    if (typeof(fieldid) !== 'undefined') {
        $.get(admin_url + 'surveys/remove_list_custom_field/' + fieldid, function(response) {
            if (response.success == true) {
                alert_float('success', response.message);
                $(field).parents('.list_custom_field').remove();
            } else {
                alert_float('warning', response.message);
            }
        }, 'json');
    } else {
        $(field).parents('.list_custom_field').remove();
    }
}
</script>
</body>

</html>