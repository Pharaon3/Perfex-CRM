<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_291 extends CI_Migration
{
    public function up()
    {
        if (!$this->db->field_exists('original_file_name', db_prefix() . 'project_files')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'project_files` ADD `original_file_name` MEDIUMTEXT DEFAULT NULL AFTER `file_name`');
        }

        if (!$this->db->field_exists('submit_redirect_url', db_prefix() . 'web_to_lead')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'web_to_lead` ADD `submit_redirect_url` MEDIUMTEXT DEFAULT NULL AFTER `success_submit_msg`');
        }

        if (!$this->db->field_exists('submit_action', db_prefix() . 'web_to_lead')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'web_to_lead` ADD `submit_action` INT(2) DEFAULT 0 AFTER `success_submit_msg`');
        }

        if (!$this->db->field_exists('submit_redirect_url', db_prefix() . 'estimate_request_forms')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'estimate_request_forms` ADD `submit_redirect_url` MEDIUMTEXT DEFAULT NULL AFTER `success_submit_msg`');
        }

        if (!$this->db->field_exists('submit_action', db_prefix() . 'estimate_request_forms')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'estimate_request_forms` ADD `submit_action` INT(2) DEFAULT 0 AFTER `success_submit_msg`');
        }

        $this->db->where('original_file_name IS NULL');
        $this->db->set('original_file_name', 'file_name', false);
        $this->db->update('project_files');
    }
}
