<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="tw-bg-neutral-100 login_admin">

    <div class="tw-max-w-md tw-mx-auto tw-pt-24 authentication-form-wrapper tw-relative tw-z-20">
        <div class="company-logo text-center">
            <?php get_dark_company_logo(); ?>
        </div>

        <h1 class="tw-text-2xl tw-text-neutral-800 text-center tw-font-semibold tw-mb-5">
            <?php echo _l('admin_auth_login_heading'); ?>
        </h1>

        <div class="tw-bg-white tw-mx-2 sm:tw-mx-6 tw-py-6 tw-px-6 sm:tw-px-8 tw-shadow tw-rounded-lg">

            <?php $this->load->view('authentication/includes/alerts'); ?>

            <?php echo form_open($this->uri->uri_string()); ?>

            <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

            <?php hooks()->do_action('after_admin_login_form_start'); ?>

            <div class="form-group">
                <label for="email" class="control-label">
                    <?php echo _l('admin_auth_login_email'); ?>
                </label>
                <input type="email" id="email" name="email" class="form-control" autofocus="1">
            </div>

            <div class="form-group">
                <label for="password" class="control-label">
                    <?php echo _l('admin_auth_login_password'); ?>
                </label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <?php if (show_recaptcha()) { ?>
            <div class="g-recaptcha tw-mb-4" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
            <?php } ?>

            <div class="form-group">
                <div class="checkbox checkbox-inline">
                    <input type="checkbox" value="estimate" id="remember" name="remember">
                    <label for="remember"> <?php echo _l('admin_auth_login_remember_me'); ?></label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <?php echo _l('admin_auth_login_button'); ?>
                </button>
            </div>

            <div class="form-group">
                <a href="<?php echo admin_url('authentication/forgot_password'); ?>">
                    <?php echo _l('admin_auth_login_fp'); ?>
                </a>
            </div>

            <?php hooks()->do_action('before_admin_login_form_close'); ?>

            <?php echo form_close(); ?>
        </div>
    </div>

</body>

</html>