<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_280 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        add_option('enable_support_menu_badges', 0);
        add_option('attach_invoice_to_payment_receipt_email', 0);
        add_option('gdpr_enable_terms_and_conditions_estimate_request_form', 0);

        $this->invoice_due_reminders();

        $this->update_short_url('invoices');
        $this->update_short_url('contracts');
        $this->update_short_url('estimates');
        $this->update_short_url('proposals');

        $this->estimate_request_feature();
    }

    public function update_short_url($feature)
    {
        $CI = &get_instance();

        $CI->db->where('short_link is NOT NULL', null, false);
        $CI->db->update(db_prefix() . $feature, [
            'short_link' => '',
        ]);
    }

    public function estimate_request_feature()
    {
        $CI = &get_instance();

        if ($CI->db->field_exists('fieldto', db_prefix() . 'customfields')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'customfields` MODIFY `fieldto` VARCHAR(30) DEFAULT NULL');
        }

        $CI->db->query(
            'CREATE TABLE IF NOT EXISTS ' . db_prefix() . 'estimate_requests (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `email` VARCHAR(100) NOT NULL,
            `submission` LONGTEXT NOT NULL,
            `last_status_change` datetime DEFAULT NULL,
            `date_estimated` datetime DEFAULT NULL,
            `from_form_id` INT DEFAULT NULL,
            `assigned` INT DEFAULT NULL,
            `status` INT DEFAULT NULL,
            `default_language` INT NOT NULL,
            `date_added` datetime NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;'
        );

        $CI->db->query(
            'CREATE TABLE IF NOT EXISTS ' . db_prefix() . 'estimate_request_status (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NOT NULL,
            `statusorder` INT DEFAULT NULL,
            `color` VARCHAR(10) DEFAULT NULL,
            `flag` VARCHAR(30) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;'
        );

        $CI->db->query(
            'CREATE TABLE IF NOT EXISTS ' . db_prefix() . 'estimate_request_forms(
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
            `form_key` VARCHAR(32) NOT NULL,
            `type` VARCHAR(100) NOT NULL,
            `name` VARCHAR(191) NOT NULL,
            `form_data` MEDIUMTEXT DEFAULT NULL,
            `recaptcha` INT DEFAULT NULL,
            `status` INT NOT NULL,
            `submit_btn_name` VARCHAR(100) DEFAULT NULL,
            `success_submit_msg` TEXT DEFAULT NULL,
            `language` VARCHAR(100) DEFAULT NULL,
            `dateadded` DATETIME DEFAULT NULL,
            `notify_type` VARCHAR(100) DEFAULT NULL,
            `notify_ids` MEDIUMTEXT DEFAULT NULL,
            `responsible` INT DEFAULT NULL,
            `notify_request_submitted` INT NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;'
        );

        $CI->db->query(
            "INSERT INTO " . db_prefix() . "estimate_request_status (`name`, `statusorder`, `color`, `flag`) VALUES
            ('Cancelled', '1', '#808080', 'cancelled'),
            ('Processing', '2', '#007bff', 'processing'),
            ('Completed', '3', '#28a745', 'completed');"
        );

        $submitted_message = '<span> Hello,&nbsp;</span><br /><br />{estimate_request_email} submitted an estimate request via the {estimate_request_form_name} form.<br /><br />You can view the request at the following link: <a href="{estimate_request_link}">{estimate_request_link}</a><br /><br />==<br /><br />{estimate_request_submitted_data}<br /><br />Kind Regards,<br /><span>{email_signature}</span>';
        create_email_template('New Estimate Request Submitted', $submitted_message, 'estimate_request', 'Estimate Request Submitted (Sent to Staff)', 'estimate-request-submitted-to-staff', 1);

        $assigned_message = '<span> Hello {estimate_request_assigned},&nbsp;</span><br /><br />Estimate request #{estimate_request_id} has been assigned to you.<br /><br />You can view the request at the following link: <a href="{estimate_request_link}">{estimate_request_link}</a><br /><br />Kind Regards,<br /><span>{email_signature}</span>';
        create_email_template('New Estimate Request Assigned', $assigned_message, 'estimate_request', 'Estimate Request Assigned (Sent to Staff)', 'estimate-request-assigned', 1);

       $submitted_message_to_user = 'Hello,<br /><br /><strong>Your request has been received.</strong><br /><br />This email is to let you know that we received your request and we will get back to you as soon as possible with more information.<br /><br />Best Regards,<br />{email_signature}';
        create_email_template('Estimate Request Received', $submitted_message_to_user, 'estimate_request', 'Estimate Request Received (Sent to User)', 'estimate-request-received-to-user', 0);
    }

    public function invoice_due_reminders()
    {
        $CI = &get_instance();

        create_email_template('Your {invoice_number} will be due soon', '<span style="font-size: 12pt;">Hi {contact_firstname} {contact_lastname}<br /><br /></span>You invoice <span style="font-size: 12pt;"><strong># {invoice_number} </strong>will be due on <strong>{invoice_duedate}</strong></span><br /><br /><span style="font-size: 12pt;">You can view the invoice on the following link: <a href="{invoice_link}">{invoice_number}</a></span><br /><br /><span style="font-size: 12pt;">Kind Regards,</span><br /><span style="font-size: 12pt;">{email_signature}</span>', 'invoice', 'Invoice Due Notice', 'invoice-due-notice', 0);

        if (!$CI->db->field_exists('last_due_reminder', db_prefix() . 'invoices')) {
            $this->db->query(
                    'ALTER TABLE `' . db_prefix() . 'invoices` ADD `last_due_reminder` DATE NULL DEFAULT NULL AFTER `last_overdue_reminder`;'
                );
        }

        add_option('invoice_due_notice_before', 2);
        add_option('invoice_due_notice_resend_after', 0);
    }
}
