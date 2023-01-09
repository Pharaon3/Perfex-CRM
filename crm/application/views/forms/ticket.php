<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl(true) ? 'rtl' : 'ltr'; ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo hooks()->apply_filters('ticket_form_title', _l('new_ticket')); ?></title>
    <?php app_external_form_header($form); ?>
    <style>
    .g-recaptcha>div {
        margin: 0 auto;
    }
    </style>
    <?php hooks()->do_action('app_ticket_form_head'); ?>
</head>

<body class="ticket_form<?php echo($this->input->get('styled') === '1' ? ' styled' : ''); ?>">
    <div class="container-fluid">
        <div class="row">
            <div
                class="<?php echo $this->input->get('col') ? $this->input->get('col') : ($this->input->get('styled') === '1' ? 'col-md-6 col-md-offset-3' : 'col-md-12'); ?>">
                <?php if ($this->input->get('with_logo')) { ?>
                <div class="text-center logo">
                    <?php get_dark_company_logo(); ?>
                </div>
                <?php } ?>
                <div class="form-col">
                    <div id="response"></div>
                    <?php echo form_open(current_full_url(), ['id' => 'ticketForm', 'class' => 'disable-on-submit']); ?>
                    <?php hooks()->do_action('ticket_form_start'); ?>

                    <?php echo render_input('subject', 'ticket_form_subject', '', 'text', ['required' => 'true']); ?>
                    <?php hooks()->do_action('ticket_form_after_subject'); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?php echo render_input('name', 'ticket_form_name', '', 'text', ['required' => 'true']); ?>
                            <?php hooks()->do_action('ticket_form_after_name'); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('email', 'ticket_form_email', '', 'email', ['required' => 'true']); ?>
                            <?php hooks()->do_action('ticket_form_after_email'); ?>
                        </div>
                    </div>
                    <?php
     $selectedDepartment = (count($departments) == 1 ? $departments[0]['departmentid'] : '');
     if ($this->input->get('department_id')) {
         $selectedDepartment = $this->input->get('department_id');
     }
    echo '<div class="' . ($this->input->get('department_id') ? 'hide' : '') . '">';
    echo render_select('department', $departments, ['departmentid', 'name'], 'ticket_form_department', $selectedDepartment, ['required' => 'true']);
    echo '</div>';
    ?>
                    <?php hooks()->do_action('ticket_form_after_department'); ?>

                    <?php echo render_select('priority', $priorities, ['priorityid', 'name'], 'ticket_form_priority', hooks()->apply_filters('new_ticket_priority_selected', 2), ['required' => 'true']); ?>
                    <?php hooks()->do_action('ticket_form_after_priority'); ?>

                    <?php
      if (get_option('services') == 1 && count($services) > 0) {
          echo '<div class="' . ($this->input->get('hide_service') == 1 ? 'hide' : '') . '">';
          echo render_select('service', $services, ['serviceid', 'name'], 'ticket_form_service', (count($services) == 1 ? $services[0]['serviceid'] : $this->input->get('service_id')));
          echo '</div>';
          hooks()->do_action('ticket_form_after_service');
      }
    ?>

                    <?php echo render_custom_fields('tickets', false, ['show_on_ticket_form' => 1]); ?>
                    <?php hooks()->do_action('ticket_form_after_custom_fields'); ?>

                    <?php echo render_textarea('message', 'ticket_form_message', '', ['required' => 'true', 'rows' => 8]); ?>
                    <?php hooks()->do_action('ticket_form_after_message'); ?>

                    <div class="attachments">
                        <div class="row attachment form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <label for="attachment"
                                    class="control-label"><?php echo _l('ticket_form_attachments'); ?></label>
                                <div class="input-group">
                                    <input type="file"
                                        extension="<?php echo str_replace('.', '', get_option('ticket_attachments_file_extensions')); ?>"
                                        filesize="<?php echo file_upload_max_size(); ?>" class="form-control"
                                        name="attachments[]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary add_more_attachments"
                                            data-max="<?php echo get_option('maximum_allowed_ticket_attachments'); ?>"
                                            type="button"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php hooks()->do_action('ticket_form_after_attachments'); ?>

                    <?php if (show_recaptcha() && $form->recaptcha == 1) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>">
                                </div>
                                <div id="recaptcha_response_field" class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_ticket_form') == 1) { ?>
                    <div class="col-md-12">
                        <div class="text-center">
                            <div class="checkbox chk">
                                <input type="checkbox" name="accept_terms_and_conditions" required="true"
                                    id="accept_terms_and_conditions"
                                    <?php echo set_checkbox('accept_terms_and_conditions', 'on'); ?>>
                                <label for="accept_terms_and_conditions">
                                    <?php echo _l('gdpr_terms_agree', terms_url()); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <div class="text-center submit-btn-wrapper">
                        <button class="btn btn-success" id="form_submit" type="submit">
                            <i class="fa fa-spinner fa-spin hide" style="margin-right: 5px;">
                            </i><?php echo _l('ticket_form_submit'); ?>
                        </button>
                    </div>

                    <?php hooks()->do_action('ticket_form_after_submit_button'); ?>

                    <?php hooks()->do_action('ticket_form_end'); ?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php app_external_form_footer($form); ?>
    <script>
    var form_id = '#ticketForm';
    $(function() {

        $(form_id).appFormValidator({

            onSubmit: function(form) {

                $("input[type=file]").each(function() {
                    if ($(this).val() === "") {
                        $(this).prop('disabled', true);
                    }
                });
                $('#form_submit .fa-spin').removeClass('hide');

                var formURL = $(form).attr("action");
                var formData = new FormData($(form)[0]);

                $.ajax({
                    type: $(form).attr('method'),
                    data: formData,
                    mimeType: $(form).attr('enctype'),
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: formURL
                }).always(function() {
                    $('#form_submit').prop('disabled', false);
                    $('#form_submit .fa-spin').addClass('hide');
                }).done(function(response) {

                    response = JSON.parse(response);
                    // In case action hook is used to redirect
                    if (response.redirect_url) {
                        if (window.top) {
                            window.top.location.href = response.redirect_url;
                        } else {
                            window.location.href = response.redirect_url;
                        }
                        return;
                    }
                    if (response.success == false) {
                        $('#recaptcha_response_field').html(response
                            .message); // error message
                    } else if (response.success == true) {
                        $(form_id).remove();
                        $('#response').html(
                            '<div class="alert alert-success" style="margin-bottom:0;">' +
                            response.message + '</div>');
                        $('html,body').animate({
                            scrollTop: $("#online_payment_form").offset().top
                        }, 'slow');
                    } else {
                        $('#response').html('Something went wrong...');
                    }
                    if (typeof(grecaptcha) != 'undefined') {
                        grecaptcha.reset();
                    }
                }).fail(function(data) {

                    if (typeof(grecaptcha) != 'undefined') {
                        grecaptcha.reset();
                    }

                    if (data.status == 422) {
                        $('#response').html(
                            '<div class="alert alert-danger">Some fields that are required are not filled properly.</div>'
                        );
                    } else {
                        $('#response').html(data.responseText);
                    }
                });
                return false;
            }
        });
    });
    </script>
    <?php hooks()->do_action('app_ticket_form_footer'); ?>
</body>

</html>