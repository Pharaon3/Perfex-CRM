<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12">
        <h4
            class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-text section-heading-verification-required">
            <?php echo _l('email_verification_required'); ?>
        </h4>
        <div class="panel_s">
            <div class="panel-body">
                <div class="alert alert-warning no-mbot verification-required-alert">
                    <h4 class="verification-required-heading">
                        <?php echo _l('email_verification_required_message'); ?>
                    </h4>
                    <p class="bold verification-required-message">
                        <?php echo _l('email_verification_required_message_mail', site_url('verification/resend')); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>