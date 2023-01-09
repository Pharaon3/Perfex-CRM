<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_283 extends CI_Migration
{
    public function up()
    {
        add_option('show_estimate_request_in_customers_area', 0);

        if (!$this->db->field_exists('default_value', db_prefix() . 'customfields')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'customfields` ADD `default_value` TEXT DEFAULT NULL');
        }

        if (!$this->db->field_exists('contact_notification', db_prefix() . 'projects')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'projects` ADD `contact_notification` INT DEFAULT 1');
        }

        if (!$this->db->field_exists('notify_contacts', db_prefix() . 'projects')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'projects` ADD `notify_contacts` TEXT DEFAULT NULL');
        }

        delete_option('scroll_responsive_tables');

        $calendarDefaultView = get_option('default_view_calendar');

        $CalendarV3toV5ViewNamesMap = [
            'month'      => 'dayGridMonth',
            'basicWeek'  => 'dayGridWeek',
            'basicDay'   => 'dayGridDay',
            'agendaWeek' => 'timeGridWeek',
            'agendaDay'  => 'timeGridDay',
        ];

        update_option('default_view_calendar', $CalendarV3toV5ViewNamesMap[$calendarDefaultView] ?? 'dayGridMonth');

        add_option('auto_backup_hour', '6');
        add_option('_v283_update_clients_theme', active_clients_theme());

        $this->db->where('short_name', 'Phillipines');
        $this->db->update('countries', ['short_name' => 'Philippines']);
    }
}
