<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_request_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Estimate Request ID',
                'key'       => '{estimate_request_id}',
                'available' => [
                    'estimate_request',
                ],
            ],
            [
                'name'      => 'Estimate Request Form Name',
                'key'       => '{estimate_request_form_name}',
                'available' => [
                    'estimate_request',
                ],
            ],
            [
                'name'      => 'Estimate Request Email',
                'key'       => '{estimate_request_email}',
                'available' => [
                    'estimate_request',
                ],
            ],
            [
                'name'      => 'Estimate Request Link',
                'key'       => '{estimate_request_link}',
                'available' => [
                    'estimate_request',
                ],
            ],
            [
                'name'      => 'Staff Assigned',
                'key'       => '{estimate_request_assigned}',
                'available' => [
                    'estimate_request',
                ],
            ],
            [
                'name'      => 'Estimate Request Status',
                'key'       => '{estimate_request_status}',
                'available' => [
                    'estimate_request',
                ],
            ],
            [
                'name'      => 'Date Submitted',
                'key'       => '{estimate_request_date_submitted}',
                'available' => [
                    'estimate_request',
                ],
            ],
            [
                'name'      => 'Submitted Data',
                'key'       => '{estimate_request_submitted_data}',
                'available' => [
                    'estimate_request',
                ],
            ],
        ];
    }

    /**
     * Estimate Request merge fields
     *
     * @param mixed $id
     *
     * @return array
     */
    public function format($id)
    {
        $this->ci->load->model('estimate_request_model');
        $fields = [];

        $fields['{estimate_request_id}']             = '';
        $fields['{estimate_request_email}']          = '';
        $fields['{estimate_request_status}']         = '';
        $fields['{estimate_request_link}']           = '';
        $fields['{estimate_request_date_submitted}'] = '';
        $fields['{estimate_request_submitted_data}'] = '';
        $fields['{estimate_request_assigned}']       = '';
        $fields['{estimate_request_form_name}']      = '';

        $request = $this->ci->estimate_request_model->get($id);

        if (!$request) {
            return $fields;
        }

        $fields['{estimate_request_form_name}']      = $request->form_data->name;
        $fields['{estimate_request_id}']             = $request->id;
        $fields['{estimate_request_date_submitted}'] = _dt($request->date_added);

        $fields['{estimate_request_link}']  = admin_url('estimate_request/view/' . $request->id);
        $fields['{estimate_request_email}'] = $request->email;

        if ($request->assigned != 0) {
            $fields['{estimate_request_assigned}'] = get_staff_full_name($request->assigned);
        }

        $status = $this->ci->estimate_request_model->get_status($request->status);

        if ($status) {
            $fields['{estimate_request_status}'] = $status->name;
        }

        $submissions    = json_decode($request->submission);
        $submitted_data = '';

        foreach ($submissions as $data) {
            $submitted_data .= $data->label . ': ';

            if (is_string($data->value)) {
                $submitted_data .= $data->value;
            } elseif (is_array($data->value)) {
                $submitted_data .= implode('<br>', $data->value);
            }
            $submitted_data .= '<br>';
        }

        $fields['{estimate_request_submitted_data}'] = $submitted_data;

        return hooks()->apply_filters('estimate_request_merge_fields', $fields, [
            'id'      => $request->id,
            'request' => $request,
        ]);
    }
}
