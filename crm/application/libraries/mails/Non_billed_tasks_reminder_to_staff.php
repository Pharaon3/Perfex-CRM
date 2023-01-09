<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Non_billed_tasks_reminder_to_staff extends App_mail_template
{
    protected $for = 'staff';

    protected $staffEmail;

    protected $staffId;

    public $slug = 'non-billed-tasks-reminder';

    public $rel_type = 'notifications';

    public function __construct($staffEmail, $staffId)
    {
        parent::__construct();

        $this->staffEmail = $staffEmail;
        $this->staffId = $staffId;
    }

    public function build()
    {
        $this->to($this->staffEmail)
        ->set_staff_id($this->staffId)
        ->set_merge_fields('staff_merge_fields', $this->staffId)
        ->set_merge_fields('notifications_merge_fields');
    }
}
