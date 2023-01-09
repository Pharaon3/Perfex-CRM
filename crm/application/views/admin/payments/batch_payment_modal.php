<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="batch-payment-modal">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php
                  echo _l('add_batch_payments') ?></h4>
            </div>
            <?php
          echo form_open('admin/payments/add_batch_payment', ['id' => 'batch-payment-form']); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group select-placeholder">
                            <select id="batch-payment-filter" class="selectpicker" name="client_filter"
                                data-width="100%"
                                data-none-selected-text="<?php echo _l('batch_payment_filter_by_customer') ?>">
                                <option value=""></option>
                                <?php foreach ($customers as $customer) { ?>
                                <option value="<?php echo $customer->userid; ?>"><?php echo $customer->company; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><strong><?php echo _l('batch_payments_table_invoice_number_heading'); ?>
                                                #</strong></th>
                                        <th><strong><?php echo _l('batch_payments_table_payment_date_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_payment_mode_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_transaction_id_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_amount_received_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_invoice_balance_due'); ?></strong>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $index => $invoice) { ?>
                                    <tr class="batch_payment_item" data-clientid="<?php echo $invoice->clientid; ?>"
                                        data-invoiceId="<?php echo $invoice->id ?>">
                                        <td>
                                            <a href="<?php echo admin_url('invoices/list_invoices/' . $invoice->id); ?>"
                                                target="_blank">
                                                <?php echo format_invoice_number($invoice->id) ?>
                                            </a><br>
                                            <a class="text-dark"
                                                href="<?php echo admin_url('clients/client/' . $invoice->clientid); ?>"
                                                target="_blank">
                                                <?php echo $invoice->company ?>
                                            </a>

                                            <input type="hidden" name="invoice[<?php echo $index ?>][invoiceid]"
                                                value="<?php echo $invoice->id?>">
                                        </td>
                                        <td><?php echo render_date_input('invoice[' . $index . '][date]', '', date(get_current_date_format(true))) ?>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="selectpicker"
                                                    name="invoice[<?php echo $index ?>][paymentmode]" data-width="100%"
                                                    data-none-selected-text="-">
                                                    <option></option>
                                                    <?php foreach ($invoice->allowed_payment_modes as $mode) { ?>
                                                    <option value="<?php echo $mode->id; ?>"><?php echo $mode->name; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td><?php echo render_input('invoice[' . $index . '][transactionid]') ?></td>
                                        <td><?php echo render_input('invoice[' . $index . '][amount]', '', '', 'number', ['max' => $invoice->total_left_to_pay]) ?>
                                        </td>
                                        <td><?php echo app_format_money($invoice->total_left_to_pay, $invoice->currency) ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 row">
                            <div class="checkbox">
                                <input type="checkbox" name="do_not_send_invoice_payment_recorded" value="1"
                                    id="do_not_send_invoice_payment_recorded">
                                <label
                                    for="do_not_send_invoice_payment_recorded"><?php echo _l('batch_payments_send_invoice_payment_recorded'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php
                      echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php
                      echo _l('apply'); ?></button>
                </div>
                <?php
              echo form_close(); ?>
            </div>
        </div>
    </div>
</div>