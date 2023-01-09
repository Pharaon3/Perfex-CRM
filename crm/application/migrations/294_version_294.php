<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property-read CI_DB_mysql_driver $db
 */
class Migration_Version_294 extends CI_Migration
{
    public function up()
    {
        add_option('show_project_on_proposal', '1');
        add_option('show_pdf_signature_proposal', '0');
        add_option('enable_honeypot_spam_validation', '0');
        add_option('staff_related_ticket_notification_to_assignee_only', '0');
        if (!$this->db->field_exists('project_id', db_prefix() . 'proposals')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'proposals` ADD `project_id` INT NULL DEFAULT NULL AFTER `proposal_to`;');
        }
    }
}