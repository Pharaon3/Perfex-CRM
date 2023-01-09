<?php

use Stripe\Subscription;

defined('BASEPATH') or exit('No direct script access allowed');

class Stripe_subscriptions_synchronizer extends Stripe_core
{
    protected $dbSubscription;

    protected $subscription;

    public function sync()
    {
        $this->ci->load->library('stripe_core');
        $this->ci->load->model('subscriptions_model');
        $this->ci->load->model('invoices_model');
        $this->ci->load->model('payments_model');

        foreach ($this->get_all_subscriptions() as $subscription) {
            $this->subscription = $subscription;

            if (!isset($subscription->metadata['pcrm-subscription-hash'])) {
                echo 'Skipping sync for subscription' . $subscription->id . ', not created from application<br /><br /><br />';

                continue;
            }

            $dbSubscription = $this->ci->subscriptions_model->get_by_hash($subscription->metadata['pcrm-subscription-hash']);

            if (!$dbSubscription) {
                echo 'Skipping sync for subscription' . $subscription->id . ', not in database<br /><br /><br />';

                continue;
            }

            $this->dbSubscription = $dbSubscription;

            if (!is_null($dbSubscription->in_test_environment)) {
                if ($dbSubscription->in_test_environment && !$this->ci->stripe_gateway->is_test() ||
                $this->ci->stripe_gateway->is_test() && !$dbSubscription->in_test_environment
                ) {
                    echo 'Skipping sync for subscription' . $subscription->id . ', environment not match<br /><br /><br />';

                    continue;
                }
            } else {
                $this->ci->subscriptions_model->update(
                    $dbSubscription->id,
                    ['in_test_environment' => $this->ci->stripe_gateway->is_test()]
                );
            }

            echo 'Syncing - <b>DB ID: [' . $dbSubscription->id . '], Stripe ID: ' . $subscription->id . '</b><br />';

            if ($this->check_stripe_client_id() === false) {
                continue;
            }

            $invoices = $this->get_subscription_invoices($subscription->id);

            foreach ($invoices->data as $invoice) {
                if ($invoice->status === 'paid' && !$this->ci->payments_model->transaction_exists($invoice->charge)) {
                    if (!defined('STRIPE_SUBSCRIPTION_INVOICE')) {
                        define('STRIPE_SUBSCRIPTION_INVOICE', true);
                    }

                    $invoiceId = $this->ci->invoices_model->add(
                        $invoiceData = create_subscription_invoice_data($dbSubscription, $invoice)
                    );

                    if ($invoiceId) {
                        $this->ci->db->where('id', $invoiceId)->update('invoices', [
                            'addedfrom' => $dbSubscription->created_from,
                        ]);

                        $this->ci->payments_model->add([
                            'paymentmode'   => 'stripe',
                            'amount'        => $invoiceData['total'],
                            'invoiceid'     => $invoiceId,
                            'transactionid' => $invoice->charge,
                        ], $dbSubscription->id);
                    }
                }
            }

            if ($subscription->default_payment_method) {
                $this->update_customer($subscription->customer, [
                    'invoice_settings' => [
                        'default_payment_method' => $subscription->default_payment_method,
                    ],
                ]);

                \Stripe\Subscription::update($subscription->id, ['default_payment_method' => '']);
            }

            $update = [
                'next_billing_cycle'     => $subscription->current_period_end,
                'stripe_subscription_id' => $subscription->id,
                'status'                 => $subscription->status,
                'date_subscribed'        => date('Y-m-d H:i:s', $subscription->start_date),
                'date'                   => date('Y-m-d', $subscription->start_date),
                'quantity'               => $subscription->items->data[0]->quantity,
                'ends_at'                => $subscription->cancel_at_period_end ? $subscription->current_period_end : null,
            ];

            if ($subscription->status == 'canceled') {
                $update['next_billing_cycle'] = null;
                // Was future and now canceled, use the same date
                if (is_null($subscription->latest_invoice)) {
                    $update['date'] = date('Y-m-d', $subscription->current_period_end);
                }
            } elseif (is_null($subscription->latest_invoice) && $subscription->status == 'active') {
                // If canceled before first payment is made?
                if (!$subscription->cancel_at_period_end) {
                    // is future
                    $update['status'] = 'future';
                    // This is the anchor period, start end
                    $update['date'] = date('Y-m-d', $subscription->current_period_end);
                }
            }
            // elseif (in_array($subscription->status, ['incomplete', 'incomplete_expired'])) {
            // } elseif (in_array($subscription->status, ['active', 'past_due', 'unpaid'])) {
            // }

            $this->ci->subscriptions_model->update($dbSubscription->id, $update);
        }
    }

    protected function get_subscription_invoices($id)
    {
        return \Stripe\Invoice::all(['subscription' => $id, 'limit' => 100]);
    }

    protected function check_stripe_client_id()
    {
        if ($this->ci->stripe_gateway->is_test()) {
            return;
        }

        $this->ci->db->where('userid', $this->dbSubscription->clientid);
        $dbClient = $this->ci->db->get('clients')->row();

        // This should not happen ever?
        if ($this->dbSubscription->stripe_subscription_id != $this->subscription->id) {
            echo '<b>Aborting, incosistent subscription ID, DB:' . $this->dbSubscription->stripe_subscription_id . ', Stripe: ' . $this->subscription->id . '</b><br /><br /><br />';

            return false;
        }

        if (empty($dbClient->stripe_id)) {
            echo 'Updating stripe_id for client "' . $dbClient->userid . '" [' . $this->subscription->customer . ']<br />';
            $this->ci->db->where('userid', $this->dbSubscription->clientid);
            $this->ci->db->update('clients', ['stripe_id' => $this->subscription->customer]);
        } else {
            echo 'Skip update client stripe_id, current: ' . $dbClient->stripe_id . ', Stripe subscription customer id: ' . $this->subscription->customer . '<br />';

            // This should not happen ever?
            if ($this->subscription->customer != $dbClient->stripe_id) {
                echo '<b>Abort, incosistent stripe id found</b><br /><br /><br />';
            }
        }
    }

    protected function get_all_subscriptions()
    {
        $hasMore       = true;
        $subscriptions = null;
        $startingAfter = null;

        do {
            $response = Subscription::all(
                array_merge(['limit' => 100, 'status' => 'all', 'created' => ['gt' => strtotime('-30 days')]], $startingAfter ? ['starting_after' => $startingAfter] : [])
            );

            if (is_null($subscriptions)) {
                $subscriptions = $response;
            } else {
                $subscriptions->data = array_merge($subscriptions->data, $response->data);
            }

            $startingAfter             = $subscriptions->data[count($subscriptions->data) - 1]->id ?? null;
            $hasMore                   = $response['has_more'];
            $subscriptions['has_more'] = $hasMore;
        } while ($hasMore);

        return $subscriptions->data ?? [];
    }
}