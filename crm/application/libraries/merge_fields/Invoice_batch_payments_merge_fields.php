<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_batch_payments_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Batch Payments list',
                'key'       => '{batch_payments_list}',
                'available' => [],
                'templates' => [
                    'invoices-batch-payments',
                ],
            ],
        ];
    }

    /**
     * Merge fields for tasks
     *
     * @param object $payments should include required fields from invoice
     * @return array
     */
    public function format($payments)
    {
        $line = '<br>';
        foreach ($payments as $payment) {
            $line .= '----------------------------------------<br>';
            $line .= _l('invoice_data_date') . ': <a href="' . site_url('invoice/' . $payment->invoiceid . '/' . $payment->hash . '/') . '">#' . format_invoice_number($payment->invoiceid) . '</a><br>';
            $line .= _l('payment_table_invoice_number') . ': ' . app_format_money($payment->amount, $payment->currency) . '<br>';
            $line .= _l('invoice_payments_table_amount_heading') . ': ' . _d($payment->date) . '<br>';
        }
        $line .= '----------------------------------------';

        $fields['{batch_payments_list}'] = $line;

        return hooks()->apply_filters('invoice_batch_payments_merge_fields', $fields);
    }
}
