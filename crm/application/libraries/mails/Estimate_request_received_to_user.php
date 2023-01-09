<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_request_received_to_user extends App_mail_template
{
    protected $estimate_request_id;

    protected $email;

    public $slug = 'estimate-request-received-to-user';

    public $rel_type = 'estimate_request';

    public function __construct($estimate_request_id, $email)
    {
        parent::__construct();
        $this->estimate_request_id = $estimate_request_id;
        $this->email = $email;
    }

    public function build()
    {
        $this->to($this->email)
            ->set_rel_id($this->estimate_request_id)
            ->set_merge_fields('estimate_request_merge_fields', $this->estimate_request_id);
    }
}
