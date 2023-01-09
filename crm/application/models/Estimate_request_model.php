<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_request_model extends App_Model
{
    const STATUS_PROCESSING = 2;

    public function __construct()
    {
        parent::__construct();
    }

    public function update_request_assigned($data)
    {
        $this->db->select('assigned');
        $this->db->where('id', $data['requestid']);
        $_old_assigned = $this->db->get(db_prefix() . 'estimate_requests')->row();

        $this->db->where('id', $data['requestid']);
        $this->db->update(db_prefix() . 'estimate_requests', [
            'assigned' => $data['assigned'],
        ]);

        $this->db->select('assigned');
        $this->db->where('id', $data['requestid']);
        $_current_assigned = $this->db->get(db_prefix() . 'estimate_requests')->row();

        if ($this->db->affected_rows() > 0) {
            if ($_current_assigned != $_old_assigned && $_old_assigned != '') {
                $_log_message = 'not_estimate_request_activity_assigned_updated';

                hooks()->do_action('estimate_request_assigned_changed', [
                    'estimate_request_id' => $data['requestid'],
                    'old_staff'           => $_old_assigned,
                    'new_staff'           => $_current_assigned,
                ]);
                log_activity($_log_message . ' staff [id:  ' . $_current_assigned->assigned . ']');
            }

            if ($_current_assigned == $_old_assigned) {
                return false;
            }

            $this->assigned_member_notification($data['requestid'], $_current_assigned->assigned);

            return true;
        }

        return false;
    }

    public function update_request_status($data)
    {
        $this->db->select('status');
        $this->db->where('id', $data['requestid']);
        $_old = $this->db->get(db_prefix() . 'estimate_requests')->row();

        $old_status = '';

        if ($_old) {
            $old_status = $this->get_status($_old->status);
            if ($old_status) {
                $old_status = $old_status->name;
            }
        }

        $affectedRows   = 0;
        $current_status = $this->get_status($data['status'])->name;

        $this->db->where('id', $data['requestid']);
        $this->db->update(db_prefix() . 'estimate_requests', [
            'status' => $data['status'],
        ]);

        $_log_message = '';

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if ($current_status != $old_status && $old_status != '') {
                $_log_message    = 'not_estimate_request_activity_status_updated';
                $additional_data = serialize([
                    get_staff_full_name(),
                    $old_status,
                    $current_status,
                ]);

                hooks()->do_action('estimate_request_status_changed', [
                    'estimate_request_id' => $data['requestid'],
                    'old_status'          => $old_status,
                    'new_status'          => $current_status,
                ]);
            }
        }

        if ($affectedRows > 0) {
            if ($_log_message == '') {
                return true;
            }

            return true;
        }

        return false;
    }

    /**
     * Get estimate_request
     * @param string $id Optional - estimate_requestid
     * @return mixed
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('*,' . db_prefix() . 'estimate_requests.id,' . db_prefix() . 'estimate_request_status.name as status_name');
        $this->db->join(db_prefix() . 'estimate_request_status', db_prefix() . 'estimate_request_status.id=' . db_prefix() . 'estimate_requests.status', 'left');

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'estimate_requests.id', $id);
            $request = $this->db->get(db_prefix() . 'estimate_requests')->row();
            if ($request) {
                if ($request->from_form_id != 0) {
                    $request->form_data = $this->get_form([
                        'id' => $request->from_form_id,
                    ]);
                }
                $request->attachments = $this->get_estimate_request_attachments($id);
            }

            return $request;
        }

        return $this->db->get(db_prefix() . 'estimate_requests')->result_array();
    }

    public function get_forms()
    {
        return $this->db->get(db_prefix() . 'estimate_request_forms')->result_array();
    }

    public function get_form($where)
    {
        $this->db->where($where);

        return $this->db->get(db_prefix() . 'estimate_request_forms')->row();
    }

    public function add_form($data)
    {
        $data                       = $this->_do_estimate_request_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);
        $data['form_key']           = app_generate_hash();
        $data['dateadded']          = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'estimate_request_forms', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Estimate Request Form Added [' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    public function update_form($id, $data)
    {
        $data                       = $this->_do_estimate_request_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'estimate_request_forms', $data);

        return ($this->db->affected_rows() > 0 ? true : false);
    }

    public function delete_form($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'estimate_request_forms');

        $this->db->where('from_form_id', $id);
        $this->db->update(db_prefix() . 'estimate_requests', [
            'from_form_id' => 0,
        ]);

        if ($this->db->affected_rows() > 0) {
            log_activity('Estimate Request Form Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    private function _do_estimate_request_form_responsibles($data)
    {
        if (isset($data['notify_request_submitted'])) {
            $data['notify_request_submitted'] = 1;
        } else {
            $data['notify_request_submitted'] = 0;
        }

        if ($data['responsible'] == '') {
            $data['responsible'] = 0;
        }
        if ($data['notify_request_submitted'] != 0) {
            if ($data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids']  = serialize([]);
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

        return $data;
    }

    /**
     * Delete estimate request  from database and all connections
     * @param mixed $id estimate request id
     * @return boolean
     */
    public function delete($id)
    {
        $affectedRows = 0;

        hooks()->do_action('before_estimate_request_deleted', $id);

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'estimate_requests');
        if ($this->db->affected_rows() > 0) {
            log_activity('Estimate Request Deleted [Deleted by: ' . get_staff_full_name() . ', ID: ' . $id . ']');

            $attachments = $this->get_estimate_request_attachments($id);
            foreach ($attachments as $attachment) {
                $this->delete_estimate_request_attachment($attachment['id']);
            }

            // Delete the tags
            $this->db->where('rel_type', 'estimate_request');
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . 'taggables');

            $affectedRows++;
        }

        return $affectedRows > 0;
    }

    // Statuses

    /**
     * Get estimate_request statuses
     * @param mixed $id status id
     * @return mixed      object if id passed else array
     */
    public function get_status($id = '', $where = [])
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'estimate_request_status')->row();
        }

        $statuses = $this->app_object_cache->get('estimate-request-all-statuses');

        if (!$statuses) {
            $this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get(db_prefix() . 'estimate_request_status')->result_array();
            $this->app_object_cache->add('estimate-request-all-statuses', $statuses);
        }

        return $statuses;
    }

    /**
     * Add new estimate_request status
     * @param array $data estimate_request status data
     */
    public function add_status($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = hooks()->apply_filters('default_estimate_request_status_color', '#757575');
        }

        if (!isset($data['statusorder'])) {
            $data['statusorder'] = total_rows(db_prefix() . 'estimate_request_status') + 1;
        }

        $this->db->insert(db_prefix() . 'estimate_request_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Estimate Request Status Added [StatusID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    /** Update statuses */
    public function update_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'estimate_request_status', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Estimate Request Status Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete estimate_request status from database
     * @param mixed $id status id
     * @return boolean
     */
    public function delete_status($id)
    {
        $current = $this->get_status($id);
        // Check if is already using in table
        if (is_reference_in_table('status', db_prefix() . 'estimate_requests', $id)) {
            return [
                'referenced' => true,
            ];
        }

        if ($current->flag != '') {
            return [
                'flag' => true,
            ];
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'estimate_request_status');
        if ($this->db->affected_rows() > 0) {
            log_activity('Estimate Request Status Deleted [StatusID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Get estimate_request attachments
     * @param mixed $id estimate_request id
     * @return array
     * @since Version 1.0.4
     */
    public function get_estimate_request_attachments($id = '', $attachment_id = '', $where = [])
    {
        $this->db->where($where);
        $idIsHash = !is_numeric($attachment_id) && strlen($attachment_id) == 32;
        if (is_numeric($attachment_id) || $idIsHash) {
            $this->db->where($idIsHash ? 'attachment_key' : 'id', $attachment_id);

            return $this->db->get(db_prefix() . 'files')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'estimate_request');
        $this->db->order_by('dateadded', 'DESC');

        return $this->db->get(db_prefix() . 'files')->result_array();
    }

    public function add_attachment_to_database($estimate_request_id, $attachment, $external = false)
    {
        $this->misc_model->add_attachment_to_database($estimate_request_id, 'estimate_request', $attachment, $external);
    }

    /**
     * Delete estimate_request attachment
     * @param mixed $id attachment id
     * @return boolean
     */
    public function delete_estimate_request_attachment($id)
    {
        $attachment = $this->get_estimate_request_attachments('', $id);
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('estimate_request') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix() . 'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('Estima Request Attachment Deleted [ID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('estimate_request') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('estimate_request') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('estimate_request') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    public function assigned_member_notification($estimate_request_id, $assigned)
    {
        if ((!empty($assigned) && $assigned != 0)) {
            $notified = add_notification([
                'description'     => 'estimate_request_assigned_to_staff',
                'touserid'        => $assigned,
                'additional_data' => serialize([]),
                'link'            => 'estimate_request/view/' . $estimate_request_id,
            ]);

            if ($notified) {
                pusher_trigger_notification([$assigned]);
            }

            $this->db->select('email');
            $this->db->where('staffid', $assigned);
            $email = $this->db->get(db_prefix() . 'staff')->row()->email;

            send_mail_template('estimate_request_assigned', $estimate_request_id, $email);

            return true;
        }

        return false;
    }

    public function get_status_by_flag($flag)
    {
        $this->db->where('flag', $flag);

        return $this->db->get(db_prefix() . 'estimate_request_status')->row();
    }
}
