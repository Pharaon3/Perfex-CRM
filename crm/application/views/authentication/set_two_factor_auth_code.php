<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="tw-bg-neutral-100 authentication two-factor-authentication-code">
    <div class="tw-max-w-md tw-mx-auto tw-pt-24 authentication-form-wrapper tw-relative tw-z-20">
        <div class="company-logo text-center">
            <?php echo get_dark_company_logo(); ?>
        </div>

        <h1 class="tw-text-2xl tw-text-neutral-800 text-center tw-font-semibold tw-mb-1">
            <?php echo _l('admin_two_factor_auth_heading'); ?>
        </h1>

        <h3 class="tw-text-xl tw-text-neutral-600 text-center tw-font-medium tw-mb-5 tw-mt-0">
            <?php echo _l('two_factor_authentication'); ?>
        </h3>

        <div class="tw-bg-white tw-mx-2 sm:tw-mx-6 tw-py-6 tw-px-6 sm:tw-px-8 tw-shadow tw-rounded-lg">

            <?php echo form_open($this->uri->uri_string()); ?>
            <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
            <?php $this->load->view('authentication/includes/alerts'); ?>
            <?php echo render_input('code', 'two_factor_authentication_code'); ?>
            <div class="form-group">
                <a href="<?php echo admin_url('authentication'); ?>"><?php echo _l('back_to_login'); ?></a>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"><?php echo _l('confirm'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</body>

</html>