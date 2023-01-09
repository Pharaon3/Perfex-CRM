<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_request extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('estimate_request_model');
        if (!staff_can('view', 'estimate_request')
            && !staff_can('view_own', 'estimate_request')
        ) {
            access_denied('Estimate Request');
        }
    }

    public function convert($estimate_request_id)
    {
        if ($this->input->post()) {
            $convert_to = $this->input->post('convert_to');
            $rel_id     = $this->input->post('rel_id');
            $rel_type   = $this->input->post('rel_type');

            if ($this->input->post('rel_id') != '' && $this->input->post('rel_type') != '') {
                if ($convert_to == 'estimate') {
                    if (!staff_can('create', 'estimates')) {
                        access_denied();
                    }
                    redirect(admin_url("{$convert_to}s/{$convert_to}?customer_id={$rel_id}&estimate_request_id={$estimate_request_id}"));
                } else {
                    if (!staff_can('create', 'proposals')) {
                        access_denied();
                    }
                    redirect(admin_url("{$convert_to}s/{$convert_to}?rel_id={$rel_id}&rel_type={$rel_type}&estimate_request_id={$estimate_request_id}"));
                }
            }

            if (!staff_can('create', 'customers')) {
                access_denied();
            }

            $default_country  = get_option('customer_default_country');
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);

            if ($data['country'] == '' && $default_country != '') {
                $data['country'] = $default_country;
            }

            $data['billing_street']  = $data['address'];
            $data['billing_city']    = $data['city'];
            $data['billing_state']   = $data['state'];
            $data['billing_zip']     = $data['zip'];
            $data['billing_country'] = $data['country'];

            $data['is_primary'] = 1;

            unset($data['requestid'], $data['convert_to'], $data['rel_type'], $data['rel_id']);

            $id = $this->clients_model->add($data, true);

            if ($id) {
                if (!staff_can('view', 'customers')) {
                    $this->db->insert(db_prefix() . 'customer_admins', [
                        'date_assigned' => date('Y-m-d H:i:s'),
                        'customer_id'   => $id,
                        'staff_id'      => get_staff_user_id(),
                    ]);
                }

                set_alert('success', _l('estimate_request_client_created_success'));

                redirect(
                    admin_url(
                        $convert_to == 'estimate' ?
                        "{$convert_to}s/{$convert_to}?customer_id={$id}&estimate_request_id={$estimate_request_id}" :
                        "{$convert_to}s/{$convert_to}?rel_id={$id}&rel_type=customer&estimate_request_id={$estimate_request_id}"
                    )
                );
            }
        }
    }

    public function update_assigned_staff()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            if (!staff_can('edit', 'estimate_request')) {
                ajax_access_denied();
            }

            if ($this->estimate_request_model->update_request_assigned($this->input->post())) {
                echo json_encode([
                    'success' => true,
                    'message' => _l('estimate_request_updated'),
                ]);
                die;
            }
            echo json_encode(['success' => false]);
            die;
        }
    }

    public function update_tags($estimate_request_id)
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            if (!staff_can('edit', 'estimate_request')) {
                ajax_access_denied();
            }

            $tags = $this->input->post('tags');
            if (handle_tags_save($tags, $estimate_request_id, 'estimate_request')) {
                echo json_encode([
                    'success' => true,
                    'message' => _l('estmate_request_tags_updated'),
                ]);
                die;
            }

            echo json_encode([
                'success' => false,
                'message' => _l('something_went_wrong'),
            ]);
            die;
        }
    }

    public function update_request_status()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            if (!staff_can('edit', 'estimate_request')) {
                ajax_access_denied();
            }

            if (
            $this->estimate_request_model->update_request_status($this->input->post())
            ) {
                echo json_encode([
                    'success'     => true,
                    'message'     => _l('estimate_request_updated'),
                    'status_name' => $this->estimate_request_model->get_status($this->input->post('status'))->name,
                ]);
                die;
            }
            echo json_encode(['success' => false]);
            die;
        }
    }

    public function view($id)
    {
        if (!staff_can('view', 'estimate_request')
            && !staff_can('view_own', 'estimate_request')) {
            access_denied('Estimate Request');
        }
        $this->load->model('leads_model');
        $data['estimate_request'] = $this->estimate_request_model->get($id);

        if (!$data['estimate_request']) {
            show_404();
        }

        if (!empty($data['estimate_request']->email)) {
            $data['lead']    = $this->leads_model->get_lead_by_email($data['estimate_request']->email);
            $data['contact'] = $this->clients_model->get_contact_by_email($data['estimate_request']->email);
        }

        $data['statuses'] = $this->estimate_request_model->get_status();
        $data['members']  = $this->staff_model->get('', ['active' => 1, 'is_not_staff' => 0, ]);
        $data['title']    = _l('estimate_request');
        $this->load->view('admin/estimate_request/estimate_request', $data);
    }

    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('estimate_request'));
        }

        if (!staff_can('delete', 'estimate_request')) {
            access_denied('Delete Lead');
        }

        $response = $this->estimate_request_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('estimate_request_lowercase')));
        } elseif ($response === true) {
            set_alert('success', _l('deleted', _l('lead')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_lowercase')));
        }

        redirect(admin_url('estimate_request'));
    }

    public function table()
    {
        if (!staff_can('view', 'estimate_request')
            && !staff_can('view_own', 'estimate_request')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('estimate_request');
    }

    // Statuses
    /* View Estimate request statuses */
    public function statuses()
    {
        if (!is_admin()) {
            access_denied('Estimate Request Statuses');
        }
        $data['statuses'] = $this->estimate_request_model->get_status();
        $data['title']    = 'Estimate Request statuses';
        $this->load->view('admin/estimate_request/manage_statuses', $data);
    }

    /* Add or update Estimate request status */
    public function status()
    {
        if (!is_admin()) {
            access_denied('Estimate Request Statuses');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $id = $this->estimate_request_model->add_status($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('estimate_request_status')));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : false, 'id' => $id]);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->estimate_request_model->update_status($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('estimate_request_status')));
                }
            }
        }
    }

    /* Delete Estimate request status from databae */
    public function delete_status($id)
    {
        if (!is_admin()) {
            access_denied('Estimate Request Statuses');
        }
        if (!$id) {
            redirect(admin_url('estimate_request/statuses'));
        }

        $response = $this->estimate_request_model->delete_status($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('estimate_request_status_lowercase')));
        } elseif (is_array($response) && isset($response['flag'])) {
            set_alert('warning', _l('not_delete_estimate_request_default_status'));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('estimate_request_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('estimate_request_status_lowercase')));
        }
        redirect(admin_url('estimate_request/statuses'));
    }

    public function index()
    {
        close_setup_menu();
        if (!staff_can('view', 'estimate_request')
            && !staff_can('view_own', 'estimate_request')) {
            access_denied('Estimate Request');
        }

        $data['staff'] = $this->staff_model->get('', ['active' => 1]);
        $data['title'] = _l('estimate_requests');
        $this->load->view('admin/estimate_request/manage_request', $data);
    }

    public function save_form_data()
    {
        if (!is_admin()) {
            ajax_access_denied();
        }

        $data = $this->input->post();

        // form data should be always sent to the request and never should be empty
        // this code is added to prevent losing the old form in case any errors
        if (!isset($data['formData']) || isset($data['formData']) && !$data['formData']) {
            echo json_encode([
                'success' => false,
            ]);
            die;
        }

        // If user paste with styling eq from some editor word and the Codeigniter XSS feature remove and apply xss=remove, may break the json.
        $data['formData'] = preg_replace('/=\\\\/m', "=''", $data['formData']);

        $_formData  = json_decode($data['formData']);
        $emailField = null;

        foreach ($_formData as $field) {
            if (isset($field->subtype) && $field->subtype === 'email') {
                $emailField = $field;

                break;
            }
        }

        if (!$emailField) {
            echo json_encode([
                'success' => false,
                'message' => _l('estimate_request_form_email_field_is_required'),
            ]);
            die;
        }

        if (!isset($emailField->required) || !$emailField->required) {
            echo json_encode([
                'success' => false,
                'message' => _l('estimate_request_form_email_field_set_to_required'),
            ]);
            die;
        }

        $this->db->where('id', $data['id']);
        $this->db->update('estimate_request_forms', [
            'form_data' => $data['formData'],
        ]);

        if ($this->db->affected_rows() > 0) {
            $response = [
                'success' => true,
                'message' => _l('updated_successfully', _l('estimate_request_form')),
           ];
        } else {
            $response = ['success' => false];
        }
        echo json_encode($response);
    }

    public function form($id = '')
    {
        if (!is_admin()) {
            access_denied('Estimate Request Form Access');
        }

        $this->load->model('roles_model');
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id   = $this->estimate_request_model->add_form($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('estimate_request_form')));
                    redirect(admin_url('estimate_request/form/' . $id));
                }
            } else {
                $success = $this->estimate_request_model->update_form($id, $this->input->post());
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('estimate_request_form')));
                }
                redirect(admin_url('estimate_request/form/' . $id));
            }
        }

        $data['formData'] = [];
        $data['title']    = _l('estimate_request_form');
        $data['statuses'] = $this->estimate_request_model->get_status();

        if ($id != '') {
            $data['form'] = $this->estimate_request_model->get_form([
                'id' => $id,
            ]);
            $data['title']    = $data['form']->name . ' - ' . _l('estimate_request_form');
            $data['formData'] = $data['form']->form_data;
        }

        $data['members'] = $this->staff_model->get('', [
            'active'       => 1,
            'is_not_staff' => 0,
        ]);

        $data['roles']     = $this->roles_model->get();
        $data['languages'] = $this->app->get_available_languages();

        $predefinedFields = [];

        $fields = [
            'email' => 'Email',
        ];

        $className = 'form-control';

        foreach ($fields as $field => $label) {
            $_field_object = new stdClass();
            $type          = 'text';
            $subtype       = '';

            if ($field == 'email') {
                $subtype = 'email';
            }

            $field_array = [
                'subtype'   => $subtype,
                'type'      => $type,
                'label'     => $label,
                'className' => $className,
                'name'      => $field,
            ];

            if ($field == 'email') {
                $field_array['required'] = true;
            }

            $_field_object->label    = $label;
            $_field_object->name     = $field;
            $_field_object->fields   = [];
            $_field_object->fields[] = $field_array;
            $predefinedFields[]      = $_field_object;
        }

        $data['bodyclass']        = 'estimate-request-form';
        $data['predefinedFields'] = $predefinedFields;

        $this->load->view('admin/estimate_request/formbuilder', $data);
    }

    public function forms($id = '')
    {
        if (!is_admin()) {
            access_denied('Estimate Request Access');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('estimate_request_form');
        }

        $data['title'] = _l('estimate_request_forms');
        $this->load->view('admin/estimate_request/forms', $data);
    }

    public function delete_form($id)
    {
        if (!is_admin()) {
            access_denied('Estimate Request Access');
        }

        $success = $this->estimate_request_model->delete_form($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('estimate_request')));
        }

        redirect(admin_url('estimate_request/forms'));
    }
}