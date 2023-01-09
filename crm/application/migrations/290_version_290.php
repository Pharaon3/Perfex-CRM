<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_290 extends CI_Migration
{
    public function up()
    {
        add_option('automatically_stop_task_timer_after_hours', 0);
        add_option('automatically_assign_ticket_to_first_staff_responding', 0);

        if (!$this->db->field_exists('hide_from_customer', db_prefix() . 'milestones')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'milestones` ADD `hide_from_customer` INT DEFAULT 0');
        }

        if (!$this->db->field_exists('cc', db_prefix() . 'tickets')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'tickets` ADD `cc` VARCHAR(191) NULL AFTER `assigned`;');
        }

        if (!$this->db->field_exists('submit_btn_bg_color', db_prefix() . 'web_to_lead')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'web_to_lead` ADD `submit_btn_bg_color` VARCHAR(10) DEFAULT "#84c529" AFTER `submit_btn_name`;');
        }

        if (!$this->db->field_exists('submit_btn_text_color', db_prefix() . 'web_to_lead')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'web_to_lead` ADD `submit_btn_text_color` VARCHAR(10) DEFAULT "#ffffff" AFTER `submit_btn_name`;');
        }

        if (!$this->db->field_exists('submit_btn_bg_color', db_prefix() . 'estimate_request_forms')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'estimate_request_forms` ADD `submit_btn_bg_color` VARCHAR(10) DEFAULT "#84c529" AFTER `submit_btn_name`;');
        }

        if (!$this->db->field_exists('submit_btn_text_color', db_prefix() . 'estimate_request_forms')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'estimate_request_forms` ADD `submit_btn_text_color` VARCHAR(10) DEFAULT "#ffffff" AFTER `submit_btn_name`;');
        }
    }
}
