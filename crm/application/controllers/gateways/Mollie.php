<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mollie extends App_Controller
{
    /**
     * Show message to the customer whether the payment is successfully
     *
     * @return mixed
     */
    public function verify_payment()
    {
        $invoiceid = $this->input->get('invoiceid');
        $hash      = $this->input->get('hash');
        check_invoice_restrictions($invoiceid, $hash);

        $this->db->where('id', $invoiceid);
        $invoice = $this->db->get(db_prefix() . 'invoices')->row();

        $oResponse = $this->mollie_gateway->fetch_payment($invoice->token);

        if ($oResponse->isSuccessful()) {
            $data = $oResponse->getData();

            if ($data['status'] == 'paid') {
                set_alert('success', _l('online_payment_recorded_success'));
            } else {
                set_alert('danger', $data['details']['failureMessage'] ?? '');
            }
        } else {
            set_alert('danger', $oResponse->getMessage());
        }

        redirect(site_url('invoice/' . $invoice->id . '/' . $invoice->hash));
    }

    /**
     * Handle the mollie webhook
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function webhook($key = null)
    {
        $trans_id  = $this->input->post('id');
        $oResponse = $this->mollie_gateway->fetch_payment($trans_id);

        log_activity('Mollie payment webhook called. ID: ' . $trans_id);

        if (!$oResponse) {
            log_activity('Mollie payment not found via webhook. ID: ' . $trans_id);

            return;
        }

        if (!$oResponse->isSuccessful()) {
            log_activity('Cannot retrieve mollie payment via webhook. ID:' . $trans_id . '. Message:' . $oResponse->getMessage());

            return;
        }

        $data = $oResponse->getData();

        if ($data['metadata']['webhookKey'] !== $key) {
            log_activity('Mollie payment webhook key does not match. Url Key: "' . $key . '", Metadata Key: "' . $data['metadata']['webhookKey'] . '"');

            return;
        }

        // log_message('error', json_encode($data));

        if ($data['status'] == 'paid') {
            $this->db->where('transactionid', $trans_id);
            $this->db->where('invoiceid', $data['metadata']['order_id']);
            $payment = $this->db->get(db_prefix() . 'invoicepaymentrecords')->row();

            if ($data['amountRemaining']['value'] == 0) {
                $this->db->where('id', $payment->id);
                $this->db->delete(db_prefix() . 'invoicepaymentrecords');
                update_invoice_status($data['metadata']['order_id']);
                log_activity('Mollie payment fully refunded. ID: ' . $trans_id);
            } elseif ($data['amountRefunded']['value'] > 0) {
                $this->db->where('id', $payment->id);
                $this->db->update(db_prefix() . 'invoicepaymentrecords', ['amount' => $data['amountRemaining']['value']]);
                update_invoice_status($data['metadata']['order_id']);
                log_activity('Mollie payment partially refunded. ID: ' . $trans_id);
            } elseif (total_rows('invoicepaymentrecords', ['invoiceid' => $data['metadata']['order_id'], 'transactionid' => $trans_id]) == 0) {
                // New payment
                $this->mollie_gateway->addPayment([
                    'amount'        => $data['amount']['value'],
                    'invoiceid'     => $data['metadata']['order_id'],
                    'paymentmethod' => $data['method'],
                    'transactionid' => $trans_id,
                ]);
            } else {
                log_activity('Mollie payment not applicable. ' . json_encode($data));
            }
        } else {
            log_activity('Mollie payment failed. Status: ' . $data['status']);
        }
    }
}
