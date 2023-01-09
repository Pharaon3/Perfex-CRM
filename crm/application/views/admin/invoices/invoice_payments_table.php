<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <table class="table table-hover no-mtop">
        <thead>
            <tr>
                <th><span class="bold"><?php echo _l('payments_table_number_heading'); ?></span></th>
                <th><span class="bold"><?php echo _l('payments_table_mode_heading'); ?></span></th>
                <th><span class="bold"><?php echo _l('payments_table_date_heading'); ?></span></th>
                <th><span class="bold"><?php echo _l('payments_table_amount_heading'); ?></span></th>
                <th><span class="bold"><?php echo _l('options'); ?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoice->payments as $payment) { ?>
            <tr class="payment">
                <td><?php echo $payment['paymentid']; ?>
                    <?php echo icon_btn('payments/pdf/' . $payment['paymentid'], 'fa-regular fa-file-pdf', 'btn-default pull-right'); ?>
                </td>
                <td><?php echo $payment['name']; ?>
                    <?php if (!empty($payment['paymentmethod'])) {
    echo ' - ' . $payment['paymentmethod'];
}
                    if ($payment['transactionid']) {
                        echo '<br />' . _l('payments_table_transaction_id', $payment['transactionid']);
                    }
                    ?>
                </td>
                <td><?php echo _d($payment['date']); ?></td>
                <td><?php echo app_format_money($payment['amount'], $invoice->currency_name); ?></td>
                <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                        <a href="<?php echo admin_url('payments/payment/' . $payment['paymentid']); ?>"
                            class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                        </a>
                        <?php if (has_permission('payments', '', 'delete')) { ?>
                        <a href="<?php echo admin_url('invoices/delete_payment/' . $payment['paymentid'] . '/' . $payment['invoiceid']); ?>"
                            class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                            <i class="fa-regular fa-trash-can fa-lg"></i>
                        </a>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>