<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php if (isset($form)) {
    echo form_hidden('form_id', $form->id);
} ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (!isset($form)) { ?>
                <div class="alert alert-info">
                    <?php echo _l('form_builder_create_form_first'); ?>
                </div>
                <?php } ?>
                <?php if (isset($form)) { ?>
                <div class="horizontal-scrollable-tabs">
                    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                    <div class="horizontal-tabs">
                        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_form_build" aria-controls="tab_form_build" role="tab" data-toggle="tab">
                                    <?php echo _l('form_builder'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tab_form_information" aria-controls="tab_form_information" role="tab"
                                    data-toggle="tab">
                                    <?php echo _l('form_information'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tab_form_integration" aria-controls="tab_form_integration" role="tab"
                                    data-toggle="tab">
                                    <?php echo _l('form_integration_code'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tab-content">
                            <?php if (isset($form)) { ?>
                            <div role="tabpanel" class="tab-pane active" id="tab_form_build">
                                <div id="build-wrap"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab_form_integration">
                                <p><?php echo _l('form_integration_code_help'); ?></p>
                                <textarea class="form-control"
                                    rows="2"><iframe width="600" height="850" src="<?php echo site_url('forms/wtl/' . $form->form_key); ?>" frameborder="0" sandbox="allow-top-navigation allow-scripts allow-forms allow-same-origin" allowfullscreen></iframe></textarea>
                                <h4 class="tw-my-5 bold">Share direct link</h4>
                                <p>
                                    <span class="label label-default">
                                        <a href="<?php echo site_url('forms/wtl/' . $form->form_key) . '?styled=1'; ?>"
                                            target="_blank">
                                            <?php echo site_url('forms/wtl/' . $form->form_key) . '?styled=1'; ?>
                                        </a>
                                    </span>
                                    <br />
                                    <br />
                                    <span class="label label-default">
                                        <a href="<?php echo site_url('forms/wtl/' . $form->form_key) . '?styled=1&with_logo=1'; ?>"
                                            target="_blank">
                                            <?php echo site_url('forms/wtl/' . $form->form_key) . '?styled=1&with_logo=1'; ?>
                                        </a>
                                    </span>
                                </p>
                                <hr />
                                <p class="bold mtop15">When placing the iframe snippet code consider the following:</p>
                                <p
                                    class="<?php echo strpos(site_url(), 'http://') !== false ? 'bold text-success' : ''; ?>">
                                    1. If the protocol of your installation is http use a http page inside the iframe.
                                </p>
                                <p
                                    class="<?php echo strpos(site_url(), 'https://') !== false ? 'bold text-success' : ''; ?>">
                                    2. If the protocol of your installation is https use a https page inside the
                                    iframe.
                                </p>
                                <p>
                                    None SSL installation will need to place the link in non ssl eq. landing page and
                                    backwards.
                                </p>
                            </div>
                            <?php } ?>
                            <div role="tabpanel" class="tab-pane<?php echo !isset($form) ? ' active' : ''; ?>"
                                id="tab_form_information">
                                <div class="tw-mx-auto tw-max-w-3xl -tw-mt-5">
                                    <div class="horizontal-tabs">
                                        <ul class="nav nav-tabs nav-tabs-horizontal tw-flex tw-justify-center"
                                            role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#tab_config_general" aria-controls="tab_config_general"
                                                    role="tab" data-toggle="tab">
                                                    General
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_config_branding" aria-controls="tab_config_branding"
                                                    role="tab" data-toggle="tab">
                                                    Branding
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_config_submission" aria-controls="tab_config_submission"
                                                    role="tab" data-toggle="tab">
                                                    Submission
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_config_notifications"
                                                    aria-controls="tab_config_notifications" role="tab"
                                                    data-toggle="tab">
                                                    Notifications
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php echo form_open($this->uri->uri_string(), ['id' => 'form_info']); ?>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="tab_config_general">
                                            <?php $value = (isset($form) ? $form->name : ''); ?>
                                            <?php echo render_input('name', 'form_name', $value); ?>
                                            <?php if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') { ?>
                                            <div class="form-group">
                                                <label for=""><?php echo _l('form_recaptcha'); ?></label><br />
                                                <div class="radio radio-inline radio-danger">
                                                    <input type="radio" name="recaptcha" id="racaptcha_0" value="0"
                                                        <?php echo isset($form) && $form->recaptcha == 0 || !isset($form) ? 'checked' : ''; ?>>
                                                    <label for="recaptcha_0"><?php echo _l('settings_no'); ?></label>
                                                </div>
                                                <div class="radio radio-inline radio-success">
                                                    <input type="radio" name="recaptcha" id="recaptcha_1" value="1"
                                                        <?php echo isset($form) && $form->recaptcha == 1 ? 'checked' : ''; ?>>
                                                    <label for="recaptcha_1"><?php echo _l('settings_yes'); ?></label>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group select-placeholder">
                                                <div class="tw-mb-1.5">
                                                    <label for="language" class="control-label">
                                                        <?php echo _l('form_lang_validation'); ?>
                                                    </label>
                                                    <select name="language" id="language"
                                                        class="form-control selectpicker"
                                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <option value=""></option>
                                                        <?php foreach ($languages as $availableLanguage) { ?>
                                                        <option value="<?php echo $availableLanguage; ?>"
                                                            <?php echo(isset($form) && $form->language == $availableLanguage) || (!isset($form) && get_option('active_language') == $availableLanguage) ? 'selected' : ''; ?>>
                                                            <?php echo ucfirst($availableLanguage); ?>
                                                        </option>
                                                        <?php  } ?>
                                                    </select>
                                                    <span>
                                                        <?php echo _l('form_lang_validation_help'); ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?php $value = (isset($form) ? $form->lead_name_prefix : ''); ?>
                                                <?php echo render_input('lead_name_prefix', 'lead_name_prefix', $value, 'text', [], [], 'mbot5'); ?>
                                                <span><?php echo _l('lead_name_prefix_help') ?></span>
                                            </div>
                                            <?php
                                 echo render_leads_source_select($sources, (isset($form) ? $form->lead_source : get_option('leads_default_source')), 'lead_import_source', 'lead_source');

                                  echo render_leads_status_select($statuses, (isset($form) ? $form->lead_status : get_option('leads_default_status')), 'lead_import_status', 'lead_status', [], true);

                                 $selected = '';
                                 foreach ($members as $staff) {
                                     if (isset($form) && $form->responsible == $staff['staffid']) {
                                         $selected = $staff['staffid'];
                                     }
                                 }
                                 ?>
                                            <?php echo render_select('responsible', $members, ['staffid', ['firstname', 'lastname']], 'leads_import_assignee', $selected); ?>

                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="tab_config_branding">
                                            <?php $value = (isset($form) ? $form->submit_btn_name : 'Submit'); ?>
                                            <?php echo render_input('submit_btn_name', 'form_btn_submit_text', $value); ?>
                                            <div class="row">
                                                <div
                                                    class="col-md-6 [&_.input-group]:!tw-mb-0 [&_.form-group]:!tw-mb-0">
                                                    <?php $value = (isset($form) ? $form->submit_btn_bg_color : '#84c529'); ?>
                                                    <?php echo render_color_picker('submit_btn_bg_color', _l('submit_button_bg_color'), $value); ?>
                                                </div>
                                                <div
                                                    class="col-md-6 [&_.input-group]:!tw-mb-0 [&_.form-group]:!tw-mb-0">
                                                    <?php $value = (isset($form) ? $form->submit_btn_text_color : '#ffffff'); ?>
                                                    <?php echo render_color_picker('submit_btn_text_color', _l('submit_button_text_color'), $value); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="tab_config_submission">
                                            <label for="" class="control-label bold">
                                                <?php echo _l('form_submit_success_action'); ?>?
                                            </label>
                                            <div class="radio radio-primary">
                                                <input type="radio" name="submit_action" value="0" id="success_message"
                                                    <?php echo isset($form) && $form->submit_action == '0' || !isset($form) ? 'checked' : ''; ?>>
                                                <label for="success_message">
                                                    <?php echo _l('form_submit_success_display_thank_you'); ?>
                                                </label>
                                            </div>
                                            <div class="radio radio-primary">
                                                <input type="radio" name="submit_action" value="1" id="website_redirect"
                                                    <?php echo isset($form) && $form->submit_action == '1' ? 'checked' : ''; ?>>
                                                <label for="website_redirect">
                                                    <?php echo _l('form_submit_success_redirect_to_website'); ?>
                                                </label>
                                            </div>
                                            <?php $value = (isset($form) ? $form->success_submit_msg : ''); ?>
                                            <?php echo render_textarea('success_submit_msg', 'form_success_submit_msg', $value); ?>
                                            <?php $value = (isset($form) ? $form->submit_redirect_url : ''); ?>
                                            <?php echo render_input('submit_redirect_url', 'form_submit_website_url', $value, 'url'); ?>
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="mark_public" id="mark_public"
                                                    <?php echo isset($form) && $form->mark_public == 1 ? 'checked' : ''; ?>>
                                                <label for="mark_public">
                                                    <?php echo _l('auto_mark_as_public'); ?>
                                                </label>
                                            </div>
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="allow_duplicate" id="allow_duplicate"
                                                    <?php echo isset($form) && $form->allow_duplicate == 1 || !isset($form) ? 'checked' : ''; ?>>
                                                <label for="allow_duplicate">
                                                    <?php echo _l('form_allow_duplicate', _l('lead_lowercase')); ?>
                                                </label>
                                            </div>
                                            <div
                                                class="duplicate-settings-wrapper row<?php echo isset($form) && $form->allow_duplicate == 1 || !isset($form) ? ' hide' : ''; ?>">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="track_duplicate_field">
                                                            <?php echo _l('track_duplicate_by_field'); ?>
                                                        </label><br />
                                                        <select class="selectpicker track_duplicate_field"
                                                            data-width="100%" name="track_duplicate_field"
                                                            id="track_duplicate_field"
                                                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                            <option value=""></option>
                                                            <?php foreach ($db_fields as $field) { ?>
                                                            <option value="<?php echo $field->name; ?>"
                                                                <?php echo isset($form) && $form->track_duplicate_field == $field->name ? 'selected' : ''; ?>
                                                                <?php echo isset($form) && $form->track_duplicate_field_and == $field->name ? 'disabled' : ''; ?>>
                                                                <?php echo $field->label; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="track_duplicate_field_and">
                                                            <?php echo _l('and_track_duplicate_by_field'); ?>
                                                        </label><br />
                                                        <select class="selectpicker track_duplicate_field_and"
                                                            data-width="100%" name="track_duplicate_field_and"
                                                            id="track_duplicate_field_and"
                                                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                            <option value=""></option>
                                                            <?php foreach ($db_fields as $field) { ?>
                                                            <option value="<?php echo $field->name; ?>"
                                                                <?php echo isset($form) && $form->track_duplicate_field_and == $field->name ? ' selected' : ''; ?>
                                                                <?php echo isset($form) && $form->track_duplicate_field == $field->name ? 'disabled' : ''; ?>>
                                                                <?php echo $field->label; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" name="create_task_on_duplicate"
                                                            id="create_task_on_duplicate"
                                                            <?php echo (isset($form) && $form->create_task_on_duplicate == 1 || !isset($form)) ? 'checked' : ''; ?>>
                                                        <label for="create_task_on_duplicate">
                                                            <i class="fa-regular fa-circle-question"
                                                                data-toggle="tooltip"
                                                                data-title="<?php echo _l('create_the_duplicate_form_data_as_task_help'); ?>">
                                                            </i>
                                                            <?php echo _l('create_the_duplicate_form_data_as_task', _l('lead_lowercase')); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="tab_config_notifications">

                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="notify_lead_imported"
                                                    id="notify_lead_imported"
                                                    <?php echo isset($form) && $form->notify_lead_imported == 1 || !isset($form) ? 'checked' : ''; ?>>
                                                <label for="notify_lead_imported">
                                                    <?php echo _l('leads_email_integration_notify_when_lead_imported'); ?>
                                                </label>
                                            </div>
                                            <div
                                                class="select-notification-settings<?php echo isset($form) && $form->notify_lead_imported == '0' ? ' hide' : ''; ?>">
                                                <div class="radio radio-primary radio-inline">
                                                    <input type="radio" name="notify_type" value="specific_staff"
                                                        id="specific_staff"
                                                        <?php echo isset($form) && $form->notify_type == 'specific_staff' || !isset($form) ? 'checked' : ''; ?>>
                                                    <label
                                                        for="specific_staff"><?php echo _l('specific_staff_members'); ?></label>
                                                </div>
                                                <div class="radio radio-primary radio-inline">
                                                    <input type="radio" name="notify_type" id="roles" value="roles"
                                                        <?php echo isset($form) && $form->notify_type == 'roles' ? 'checked' : ''; ?>>
                                                    <label for="roles"><?php echo _l('staff_with_roles'); ?></label>
                                                </div>
                                                <div class="radio radio-primary radio-inline">
                                                    <input type="radio" name="notify_type" id="assigned"
                                                        value="assigned"
                                                        <?php echo isset($form) && $form->notify_type == 'assigned' ? 'checked' : ''; ?>>
                                                    <label
                                                        for="assigned"><?php echo _l('notify_assigned_user'); ?></label>
                                                </div>
                                                <div class="clearfix mtop15"></div>
                                                <div id="specific_staff_notify"
                                                    class="<?php echo isset($form) && $form->notify_type != 'specific_staff' ? 'hide' : ''; ?>">
                                                    <?php
                                                        $selected = [];
                                                        if (isset($form) && $form->notify_type == 'specific_staff') {
                                                            $selected = unserialize($form->notify_ids);
                                                        }
                                                        ?>
                                                    <?php echo render_select('notify_ids_staff[]', $members, ['staffid', ['firstname', 'lastname']], 'leads_email_integration_notify_staff', $selected, ['multiple' => true]); ?>
                                                </div>
                                                <div id="role_notify"
                                                    class="<?php echo isset($form) && $form->notify_type != 'roles' || !isset($form) ? 'hide' : ''; ?>">
                                                    <?php
                                                        $selected = [];
                                                        if (isset($form) && $form->notify_type == 'roles') {
                                                            $selected = unserialize($form->notify_ids);
                                                        }
                                                        ?>
                                                    <?php echo render_select('notify_ids_roles[]', $roles, ['roleid', ['name']], 'leads_email_integration_notify_roles', $selected, ['multiple' => true]); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-bottom-toolbar text-right">
                                            <button type="submit" class="btn btn-primary">
                                                <?php echo _l('submit'); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/form-builder/form-builder.min.js'); ?>"></script>
<script>
var buildWrap = document.getElementById('build-wrap');
var formData = <?php echo json_encode($formData); ?>;

if (formData.length) {
    // If user paste with styling eq from some editor word and the Codeigniter XSS feature remove and apply xss=remove, may break the json.
    formData = formData.replace(/=\\/gm, "=''");
}
</script>
<?php $this->load->view('admin/includes/_form_js_formatter'); ?>
<script>
$(function() {

    $('body').on('blur', '.form-field.editing', function() {
        $.Shortcuts.start();
    });

    $('body').on('focus', '.form-field.editing', function() {
        $.Shortcuts.stop();
    });

    var formBuilder = $(buildWrap).formBuilder(fbOptions);
    var $create_task_on_duplicate = $('#create_task_on_duplicate');

    $('#allow_duplicate').on('change', function() {
        $('.duplicate-settings-wrapper').toggleClass('hide');
    });

    $('#notify_lead_imported').on('change', function() {
        $('.select-notification-settings').toggleClass('hide');
    });

    $('#track_duplicate_field,#track_duplicate_field_and').on('change', function() {
        var selector = ($(this).hasClass('track_duplicate_field') ? 'track_duplicate_field_and' :
            'track_duplicate_field')
        $('#' + selector + ' option').removeAttr('disabled', true);
        var val = $(this).val();
        if (val !== '') {
            $('#' + selector + ' option[value="' + val + '"]').attr('disabled', true);
        }
        $('#' + selector + '').selectpicker('refresh');
    });

    setTimeout(function() {
        $(".form-builder-save").wrap("<div class='btn-bottom-toolbar text-right'></div>");
        $btnToolbar = $('body').find('#tab_form_build .btn-bottom-toolbar');
        $btnToolbar = $('#tab_form_build').append($btnToolbar);
        $btnToolbar.find('.btn').addClass('btn-primary');
    }, 100);

    $('body').on('click', '.save-template', function() {
        $.post(admin_url + 'leads/save_form_data', {
            formData: formBuilder.formData,
            id: $('input[name="form_id"]').val()
        }).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }
        });
    });

    appValidateForm('#form_info', {
        name: 'required',
        lead_source: 'required',
        lead_status: 'required',
        language: 'required',
        submit_btn_name: 'required',
        submit_btn_bg_color: 'required',
        submit_btn_text_color: 'required',
        responsible: {
            required: {
                depends: function(element) {
                    var isRequiredByNotifyType = ($('input[name="notify_type"]:checked').val() ==
                        'assigned') ? true : false;
                    var isRequiredByAssignTask = ($create_task_on_duplicate.is(':checked')) ? true :
                        false;
                    var isRequired = isRequiredByNotifyType || isRequiredByAssignTask;
                    if (isRequired) {
                        $('[for="responsible"]').find('.req').removeClass('hide');
                    } else {
                        $(element).next('p.text-danger').remove();
                        $('[for="responsible"]').find('.req').addClass('hide');
                    }
                    return isRequired;
                }
            }
        },
        success_submit_msg: {
            required: {
                depends: function(element) {
                    var isRequired = ($('input[name="submit_action"]:checked').val() === '0') ?
                        true : false;
                    if (isRequired) {
                        $('[for="success_submit_msg"]').find('.req').removeClass('hide');
                    } else {
                        $(element).next('p.text-danger').remove();
                        $('[for="success_submit_msg"]').find('.req').addClass('hide');
                    }
                    return isRequired;
                }
            }
        },
        submit_redirect_url: {
            required: {
                depends: function(element) {
                    var isRequired = ($('input[name="submit_action"]:checked').val() === '1') ?
                        true : false;
                    if (isRequired) {
                        $('[for="submit_redirect_url"]').find('.req').removeClass('hide');
                    } else {
                        $(element).next('p.text-danger').remove();
                        $('[for="submit_redirect_url"]').find('.req').addClass('hide');
                    }
                    return isRequired;
                }
            }
        }
    });

    var $notifyTypeInput = $('input[name="notify_type"]');
    $notifyTypeInput.on('change', function() {
        $('#form_info').validate().checkForm()
    });
    $notifyTypeInput.trigger('change');

    $create_task_on_duplicate.on('change', function() {
        $('#form_info').validate().checkForm()
    });

    $create_task_on_duplicate.trigger('change');

    var $submitActionInput = $('input[name="submit_action"]');
    $submitActionInput.on('change', function() {
        $('#form_info').validate().checkForm();

        if ($('input[name="submit_action"]:checked').val() === '1') {
            $('[app-field-wrapper="submit_redirect_url"]').removeClass('hide');
            $('[app-field-wrapper="success_submit_msg"]').addClass('hide');
        } else {
            $('[app-field-wrapper="success_submit_msg"]').removeClass('hide');
            $('[app-field-wrapper="submit_redirect_url"]').addClass('hide');
        }
    });
    $submitActionInput.trigger('change');
});
</script>
</body>

</html>