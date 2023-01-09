<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Omnipay\Omnipay;
use Money\Exception\UnknownCurrencyException;

class Mollie_gateway extends App_gateway
{
    public function __construct()
    {

         /**
         * Call App_gateway __construct function
         */
        parent::__construct();
        /**
         * REQUIRED
         * Gateway unique id
         * The ID must be alpha/alphanumeric
         */
        $this->setId('mollie');

        /**
         * REQUIRED
         * Gateway name
         */
        $this->setName('Mollie');

        /**
         * Add gateway settings
        */
        $this->setSettings([
            [
                'name'      => 'api_key',
                'encrypted' => true,
                'label'     => 'settings_paymentmethod_mollie_api_key',
            ],
            [
                'name'          => 'description_dashboard',
                'label'         => 'settings_paymentmethod_description',
                'type'          => 'textarea',
                'default_value' => 'Payment for Invoice {invoice_number}',
            ],
            [
                'name'          => 'currencies',
                'label'         => 'currency',
                'default_value' => 'EUR',
            ],
            [
                'name'          => 'test_mode_enabled',
                'type'          => 'yes_no',
                'default_value' => 1,
                'label'         => 'settings_paymentmethod_testing_mode',
            ],
        ]);
    }

    /**
     * Process the payment
     *
     * @param  array $data
     *
     * @return mixed
     */
    public function process_payment($data)
    {
        $gateway = Omnipay::create('Mollie');
        $gateway->setApiKey($this->decryptSetting('api_key'));

        $webhookKey    = app_generate_hash();
        $invoiceNumber = format_invoice_number($data['invoice']->id);
        $description   = str_replace('{invoice_number}', $invoiceNumber, $this->getSetting('description_dashboard'));
        $returnUrl     = site_url('gateways/mollie/verify_payment?invoiceid=' . $data['invoice']->id . '&hash=' . $data['invoice']->hash);
        $webhookUrl    = site_url('gateways/mollie/webhook/' . $webhookKey);
        $invoiceUrl    = site_url('invoice/' . $data['invoice']->id . '/' . $data['invoice']->hash);

        try {
            $oResponse = $gateway->purchase([
            'amount'      => number_format($data['amount'], 2, '.', ''),
            'currency'    => $data['invoice']->currency_name,
            'description' => $description,
            'returnUrl'   => $returnUrl,
            'notifyUrl'   => $webhookUrl,
            'metadata'    => [
                'order_id'   => $data['invoice']->id,
                'webhookKey' => $webhookKey,
            ],
        ])->send();
        } catch (UnknownCurrencyException $e) {
            set_alert('danger', 'Invalid currency, ' . $e->getMessage());
            redirect($invoiceUrl);
        }

        // Add the token to database
        $this->ci->db->where('id', $data['invoiceid']);
        $this->ci->db->update(db_prefix() . 'invoices', [
            'token' => $oResponse->getTransactionReference(),
        ]);

        if ($oResponse->isRedirect()) {
            $oResponse->redirect();
        } elseif ($oResponse->isPending()) {
            echo 'Pending, Reference: ' . $oResponse->getTransactionReference();
        } else {
            set_alert('danger', $oResponse->getData()['detail']);
            redirect($invoiceUrl);
        }
    }

    /**
     * Retrieve payment from Mollie
     *
     * @param  string $transactionId
     *
     * @return mixed
     */
    public function fetch_payment($transactionId)
    {
        $gateway = Omnipay::create('Mollie');
        $gateway->setApiKey($this->decryptSetting('api_key'));

        return $gateway->fetchTransaction([
            'transactionReference' => $transactionId,
        ])->send();
    }
}
