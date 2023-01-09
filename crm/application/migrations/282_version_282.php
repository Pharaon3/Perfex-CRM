<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_282 extends CI_Migration
{
    public function up()
    {
        if (!$this->db->field_exists('assigned', db_prefix() . 'task_checklist_items	')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'task_checklist_items` ADD `assigned` INT DEFAULT NULL');
        }
    }
}
