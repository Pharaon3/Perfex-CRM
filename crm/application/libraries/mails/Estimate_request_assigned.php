<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_request_assigned extends App_mail_template
{
    protected $estimate_request_id;

    protected $staff_email;

    protected $for = 'staff';

    public $slug = 'estimate-request-assigned';

    public $rel_type = 'estimate_request';

    public function __construct($estimate_request_id, $staff_email)
    {
        parent::__construct();
        $this->estimate_request_id = $estimate_request_id;
        $this->staff_email = $staff_email;
    }

    public function build()
    {
        $this->to($this->staff_email)
            ->set_rel_id($this->estimate_request_id)
            ->set_merge_fields('estimate_request_merge_fields', $this->estimate_request_id);
    }
}
