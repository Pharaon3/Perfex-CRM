<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="horizontal-scrollable-tabs panel-full-width-tabs">
    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
            <li role="presentation" class="active">
                <a href="#email_config" aria-controls="email_config" role="tab"
                    data-toggle="tab"><?php echo _l('settings_smtp_settings_heading'); ?></a>
            </li>
            <li role="presentation">
                <a href="#email_queue" aria-controls="email_queue" role="tab"
                    data-toggle="tab"><?php echo _l('email_queue'); ?></a>
            </li>
        </ul>
    </div>
</div>
<div class="tab-content mtop15">
    <div role="tabpanel" class="tab-pane active" id="email_config">
        <?php
        if (! empty(get_option('smtp_email'))) {
            $usingGmailAccount = preg_match('/gmail.com/', get_option('smtp_email'));
            if ($usingGmailAccount) {
                ?>
        <div class="alert alert-warning">
            <p class="bold">
                Starting from May 30, 2022, Google will no longer support sign in to your Google Account using your
                email/username and account password.
            </p>
            <p>
                If you are using your Google Account password to connect to SMTP, it's highly recommended to <span
                    class="bold">update your password with an App Password</span> to avoid any email sending
                disruptions, find more information on how to generate App Password for your Google Account at the
                following link: <a
                    href="https://support.google.com/accounts/answer/185833?hl=en">https://support.google.com/accounts/answer/185833?hl=en</a>
            </p>
        </div>
        <?php
            }
        }
        ?>
        <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
        <input type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1" />
        <input type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1" />
        <h4 style="margin-top:-20px;" class="tw-font-semibold"><?php echo _l('settings_smtp_settings_heading'); ?>
            <small class="text-muted"><?php echo _l('settings_smtp_settings_subheading'); ?></small>
        </h4>
        <hr />
        <div class="form-group">

            <label for="mail_engine"><?php echo _l('mail_engine'); ?></label><br />
            <div class="radio radio-inline radio-primary">
                <input type="radio" name="settings[mail_engine]" id="phpmailer" value="phpmailer" <?php if (get_option('mail_engine') == 'phpmailer') {
            echo 'checked';
        } ?>>
                <label for="phpmailer">PHPMailer</label>
            </div>

            <div class="radio radio-inline radio-primary">
                <input type="radio" name="settings[mail_engine]" id="codeigniter" value="codeigniter" <?php if (get_option('mail_engine') == 'codeigniter') {
            echo 'checked';
        } ?>>
                <label for="codeigniter">CodeIgniter</label>
            </div>
            <hr />
            <?php if (get_option('email_protocol') == 'mail') { ?>
            <div class="alert alert-warning">
                The "mail" protocol is not the recommended protocol to send emails, you should strongly consider
                configuring the "SMTP" protocol to avoid any distruptions and delivery issues.
            </div>
            <?php } ?>
            <label for="email_protocol"><?php echo _l('email_protocol'); ?></label><br />
            <div class="radio radio-inline radio-primary">
                <input type="radio" name="settings[email_protocol]" id="smtp" value="smtp" <?php if (get_option('email_protocol') == 'smtp') {
            echo 'checked';
        } ?>>
                <label for="smtp">SMTP</label>
            </div>

            <div class="radio radio-inline radio-primary">
                <input type="radio" name="settings[email_protocol]" id="sendmail" value="sendmail" <?php if (get_option('email_protocol') == 'sendmail') {
            echo 'checked';
        } ?>>
                <label for="sendmail">Sendmail</label>
            </div>

            <div class="radio radio-inline radio-primary">
                <input type="radio" name="settings[email_protocol]" id="mail" value="mail" <?php if (get_option('email_protocol') == 'mail') {
            echo 'checked';
        } ?>>
                <label for="mail">Mail</label>
            </div>
        </div>
        <div class="smtp-fields<?php if (get_option('email_protocol') == 'mail') {
            echo ' hide';
        } ?>">
            <div class="form-group mtop15">
                <label for="smtp_encryption"><?php echo _l('smtp_encryption'); ?></label><br />
                <select name="settings[smtp_encryption]" class="selectpicker" data-width="100%">
                    <option value="" <?php if (get_option('smtp_encryption') == '') {
            echo 'selected';
        } ?>><?php echo _l('smtp_encryption_none'); ?></option>
                    <option value="ssl" <?php if (get_option('smtp_encryption') == 'ssl') {
            echo 'selected';
        } ?>>SSL</option>
                    <option value="tls" <?php if (get_option('smtp_encryption') == 'tls') {
            echo 'selected';
        } ?>>TLS</option>
                </select>
            </div>
            <?php echo render_input('settings[smtp_host]', 'settings_email_host', get_option('smtp_host')); ?>
            <?php echo render_input('settings[smtp_port]', 'settings_email_port', get_option('smtp_port')); ?>
        </div>
        <?php echo render_input('settings[smtp_email]', 'settings_email', get_option('smtp_email')); ?>
        <div class="smtp-fields<?php if (get_option('email_protocol') == 'mail') {
            echo ' hide';
        } ?>">
            <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
                data-title="<?php echo _l('smtp_username_help'); ?>"></i>
            <?php echo render_input('settings[smtp_username]', 'smtp_username', get_option('smtp_username')); ?>
            <?php
            $ps = get_option('smtp_password');
            if (!empty($ps)) {
                if (false == $this->encryption->decrypt($ps)) {
                    $ps = $ps;
                } else {
                    $ps = $this->encryption->decrypt($ps);
                }
            }
            echo render_input('settings[smtp_password]', 'settings_email_password', $ps, 'password', ['autocomplete' => 'off']); ?>
        </div>
        <?php echo render_input('settings[smtp_email_charset]', 'settings_email_charset', get_option('smtp_email_charset')); ?>
        <?php echo render_input('settings[bcc_emails]', 'bcc_all_emails', get_option('bcc_emails')); ?>
        <?php echo render_textarea('settings[email_signature]', 'settings_email_signature', get_option('email_signature'), ['data-entities-encode' => 'true']); ?>
        <hr />
        <?php echo render_textarea('settings[email_header]', 'email_header', get_option('email_header'), ['rows' => 15, 'data-entities-encode' => 'true']); ?>
        <?php echo render_textarea('settings[email_footer]', 'email_footer', get_option('email_footer'), ['rows' => 15, 'data-entities-encode' => 'true']); ?>
        <hr />
        <h4><?php echo _l('settings_send_test_email_heading'); ?></h4>
        <p class="text-muted"><?php echo _l('settings_send_test_email_subheading'); ?></p>
        <div class="form-group">
            <div class="input-group">
                <input type="email" class="form-control" name="test_email" data-ays-ignore="true"
                    placeholder="<?php echo _l('settings_send_test_email_string'); ?>">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-info test_email">Test</button>
                </div>
            </div>
        </div>

    </div>
    <div role="tabpanel" class="tab-pane" id="email_queue">
        <?php if (get_option('cron_has_run_from_cli') != '1') { ?>
        <div class="alert alert-danger">
            This feature requires a properly configured cron job. Before activating the feature, make sure that the <a
                href="<?php echo admin_url('settings?group=cronjob'); ?>">cron job</a> is configured as explanation in
            the documentation.
        </div>
        <?php } ?>
        <?php render_yes_no_option('email_queue_enabled', 'email_queue_enabled', 'To speed up the emailing process, the system will add the emails in queue and will send them via cron job, make sure that the cron job is properly configured in order to use this feature.'); ?>
        <hr />
        <?php render_yes_no_option('email_queue_skip_with_attachments', 'email_queue_skip_attachments', 'Most likely you will encounter problems with the email queue if the system needs to add big files to the queue. If you plan to use this option consult with your server administrator/hosting provider to increase the max_allowed_packet and wait_timeout options in your server config, otherwise when this option is set to yes the system won\'t add emails with attachments in the queue and will be sent immediately.'); ?>
        <?php
        $queueEmails = $this->email->get_queue_emails();
        ?>
        <hr />
        <h4 class="mbot15"><?php echo _l('email_queue'); ?></h4>

        <table class="table dt-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>To</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($queueEmails as $email) {
            $headers = unserialize($email->headers); ?>
                <tr>
                    <td><?php echo $headers['subject']; ?></td>
                    <td><?php echo $email->email; ?></td>
                    <td><?php echo $email->status; ?></td>
                    <td>
                        <a href="<?php echo admin_url('emails/delete_queued_email/' . $email->id); ?>"
                            class="text-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php
        } ?>
            </tbody>
        </table>
    </div>
</div>