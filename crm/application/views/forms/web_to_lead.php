<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl(true) ? 'rtl' : 'ltr'; ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $form->name; ?></title>
    <?php app_external_form_header($form); ?>
    <?php hooks()->do_action('app_web_to_lead_form_head'); ?>
    <style>
    #form_submit:hover {
        background-color: <?php echo adjust_color_brightness($form->submit_btn_bg_color, -30) ?> !important;
    }
    </style>
</head>

<body class="web-to-lead <?php echo $form->form_key . ($this->input->get('styled') === '1' ? ' styled' : ''); ?>">
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
                    <?php echo form_open_multipart($this->uri->uri_string(), ['id' => $form->form_key, 'class' => 'disable-on-submit']); ?>
                    <?php hooks()->do_action('web_to_lead_form_start'); ?>
                    <?php echo form_hidden('key', $form->form_key); ?>
                    <div class="row [&_p]:tw-mb-5">
                        <?php foreach ($form_fields as $field) {
    render_form_builder_field($field);
} ?>
                        <?php if (show_recaptcha() && $form->recaptcha == 1) { ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>">
                                </div>
                                <div id="recaptcha_response_field" class="text-danger"></div>
                            </div>
                            <?php } ?>
                            <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_lead_form') == 1) { ?>
                            <div class="col-md-12">
                                <div class="checkbox chk">
                                    <input type="checkbox" name="accept_terms_and_conditions" required="true"
                                        id="accept_terms_and_conditions"
                                        <?php echo set_checkbox('accept_terms_and_conditions', 'on'); ?>>
                                    <label for="accept_terms_and_conditions">
                                        <?php echo _l('gdpr_terms_agree', terms_url()); ?>
                                    </label>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="clearfix"></div>
                            <div class="text-left col-md-12 submit-btn-wrapper">
                                <button class="btn" id="form_submit" type="submit"
                                    style="color: <?php echo $form->submit_btn_text_color ?>;background-color: <?php echo $form->submit_btn_bg_color ?>;">
                                    <i class="fa fa-spinner fa-spin hide" style="margin-right: 2px;"></i>
                                    <?php echo $form->submit_btn_name; ?></button>
                            </div>
                        </div>

                        <?php hooks()->do_action('web_to_lead_form_end'); ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php app_external_form_footer($form); ?>
        <script>
        var form_id = '#<?php echo $form->form_key; ?>';
        var form_redirect_url = '<?php echo $form->submit_action == 1 ? $form->submit_redirect_url : 0; ?>';
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
                        if (form_redirect_url !== '0') {
                            if (window.top) {
                                window.top.location.href = form_redirect_url;
                            } else {
                                window.location.href = form_redirect_url;
                            }
                            return;
                        } else if (response.redirect_url) {
                            // In case action hook is used to redirect
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
                            $('#response').html('<div class="alert alert-success">' +
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
        <?php hooks()->do_action('app_web_to_lead_form_footer'); ?>
</body>

</html>