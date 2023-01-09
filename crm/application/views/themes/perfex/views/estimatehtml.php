<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop15 preview-top-wrapper">
    <div class="row">
        <div class="col-md-3">
            <div class="mbot30">
                <div class="estimate-html-logo">
                    <?php echo get_dark_company_logo(); ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="top" data-sticky data-sticky-class="preview-sticky-header">
        <div class="container preview-sticky-container">
            <div class="sm:tw-flex sm:tw-justify-between -tw-mx-4">
                <div class="sm:tw-self-end">
                    <h3 class="bold tw-my-0 estimate-html-number">
                        <span class="sticky-visible hide tw-mb-2">
                            <?php echo format_estimate_number($estimate->id); ?>
                        </span>
                    </h3>
                    <span class="estimate-html-status">
                        <?php echo format_estimate_status($estimate->status, '', true); ?>
                    </span>
                </div>

                <div class="tw-flex tw-items-end tw-space-x-2 tw-mt-3 sm:tw-mt-0">
                    <?php echo form_open($this->uri->uri_string(), ['class' => 'action-button']); ?>
                    <button type="submit" name="estimatepdf" class="btn btn-default action-button download"
                        value="estimatepdf">
                        <i class="fa-regular fa-file-pdf"></i>
                        <?php echo _l('clients_invoice_html_btn_download'); ?>
                    </button>
                    <?php echo form_close(); ?>
                    <?php if (is_client_logged_in() && has_contact_permission('estimates')) { ?>
                    <a href="<?php echo site_url('clients/estimates/'); ?>"
                        class="btn btn-default action-button go-to-portal">
                        <?php echo _l('client_go_to_dashboard'); ?>
                    </a>
                    <?php } ?>
                    <?php
                        // Is not accepted, declined and expired
                  if ($estimate->status != 4 && $estimate->status != 3 && $estimate->status != 5) {
                      echo form_open($this->uri->uri_string(), ['class' => 'action-button']);
                      echo form_hidden('estimate_action', 3);
                      echo '<button type="submit" data-loading-text="' . _l('wait_text') . '" autocomplete="off" class="btn btn-default action-button accept"><i class="fa fa-remove"></i> ' . _l('clients_decline_estimate') . '</button>';
                      echo form_close();
                  }
                  // Is not accepted, declined and expired
                  if ($estimate->status != 4 && $estimate->status != 3 && $estimate->status != 5) {
                      $can_be_accepted = true;
                      if ($identity_confirmation_enabled == '0') {
                          echo form_open($this->uri->uri_string(), ['class' => 'action-button']);
                          echo form_hidden('estimate_action', 4);
                          echo '<button type="submit" data-loading-text="' . _l('wait_text') . '" autocomplete="off" class="btn btn-success action-button accept"><i class="fa fa-check"></i> ' . _l('clients_accept_estimate') . '</button>';
                          echo form_close();
                      } else {
                          echo '<button type="button" id="accept_action" class="btn btn-success action-button accept"><i class="fa fa-check"></i> ' . _l('clients_accept_estimate') . '</button>';
                      }
                  } elseif ($estimate->status == 3) {
                      if (($estimate->expirydate >= date('Y-m-d') || !$estimate->expirydate) && $estimate->status != 5) {
                          $can_be_accepted = true;
                          if ($identity_confirmation_enabled == '0') {
                              echo form_open($this->uri->uri_string(), ['class' => 'action-button']);
                              echo form_hidden('estimate_action', 4);
                              echo '<button type="submit" data-loading-text="' . _l('wait_text') . '" autocomplete="off" class="btn btn-success action-button accept"><i class="fa fa-check"></i> ' . _l('clients_accept_estimate') . '</button>';
                              echo form_close();
                          } else {
                              echo '<button type="button" id="accept_action" class="btn btn-success action-button accept"><i class="fa fa-check"></i> ' . _l('clients_accept_estimate') . '</button>';
                          }
                      }
                  }
                  ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="panel_s tw-mt-6">
        <div class="panel-body">
            <div class="col-md-10 col-md-offset-1">
                <div class="row mtop20">
                    <div class="col-md-6 col-sm-6 transaction-html-info-col-left">
                        <h4 class="bold estimate-html-number"><?php echo format_estimate_number($estimate->id); ?></h4>
                        <address class="estimate-html-company-info tw-text-neutral-500 tw-text-normal">
                            <?php echo format_organization_info(); ?>
                        </address>
                    </div>
                    <div class="col-sm-6 text-right transaction-html-info-col-right">
                        <span class="tw-font-medium tw-text-neutral-600 estimate_to">
                            <?php echo _l('estimate_to'); ?>
                        </span>
                        <address class="estimate-html-customer-billing-info tw-text-neutral-500 tw-text-normal">
                            <?php echo format_customer_info($estimate, 'estimate', 'billing'); ?>
                        </address>
                        <!-- shipping details -->
                        <?php if ($estimate->include_shipping == 1 && $estimate->show_shipping_on_estimate == 1) { ?>
                        <span class="tw-font-medium tw-text-neutral-700 estimate_ship_to">
                            <?php echo _l('ship_to'); ?>
                        </span>
                        <address class="estimate-html-customer-shipping-info tw-text-neutral-500 tw-text-normal">
                            <?php echo format_customer_info($estimate, 'estimate', 'shipping'); ?>
                        </address>
                        <?php } ?>
                        <p class="estimate-html-date tw-mb-0 tw-text-normal">
                            <span class="tw-font-medium tw-text-neutral-700">
                                <?php echo _l('estimate_data_date'); ?>:
                            </span>
                            <?php echo _d($estimate->date); ?>
                        </p>
                        <?php if (!empty($estimate->expirydate)) { ?>
                        <p class="estimate-html-expiry-date tw-mb-0 tw-text-normal">
                            <span class="tw-font-medium tw-text-neutral-700">
                                <?php echo _l('estimate_data_expiry_date'); ?>:
                            </span>
                            <?php echo _d($estimate->expirydate); ?>
                        </p>
                        <?php } ?>
                        <?php if (!empty($estimate->reference_no)) { ?>
                        <p class="estimate-html-reference-no tw-mb-0 tw-text-normal">
                            <span class="tw-font-medium tw-text-neutral-700"><?php echo _l('reference_no'); ?>:</span>
                            <?php echo $estimate->reference_no; ?>
                        </p>
                        <?php } ?>
                        <?php if ($estimate->sale_agent != 0 && get_option('show_sale_agent_on_estimates') == 1) { ?>
                        <p class="estimate-html-sale-agent tw-mb-0 tw-text-normal">
                            <span
                                class="tw-font-medium tw-text-neutral-700"><?php echo _l('sale_agent_string'); ?>:</span>
                            <?php echo get_staff_full_name($estimate->sale_agent); ?>
                        </p>
                        <?php } ?>
                        <?php if ($estimate->project_id != 0 && get_option('show_project_on_estimate') == 1) { ?>
                        <p class="estimate-html-project tw-mb-0 tw-text-normal">
                            <span class="tw-font-medium tw-text-neutral-700"><?php echo _l('project'); ?>:</span>
                            <?php echo get_project_name_by_id($estimate->project_id); ?>
                        </p>
                        <?php } ?>
                        <?php $pdf_custom_fields = get_custom_fields('estimate', ['show_on_pdf' => 1, 'show_on_client_portal' => 1]);
                  foreach ($pdf_custom_fields as $field) {
                      $value = get_custom_field_value($estimate->id, $field['id'], 'estimate');
                      if ($value == '') {
                          continue;
                      } ?>
                        <p class="tw-mb-0 tw-text-normal">
                            <span class="tw-font-medium tw-text-neutral-700">
                                <?php echo $field['name']; ?>:
                            </span>
                            <?php echo $value; ?>
                        </p>
                        <?php
                  } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <?php
                                $items = get_items_table_data($estimate, 'estimate');
                                echo $items->table();
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-6">
                        <table class="table text-right tw-text-normal">
                            <tbody>
                                <tr id="subtotal">
                                    <td>
                                        <span class="bold tw-text-neutral-700">
                                            <?php echo _l('estimate_subtotal'); ?>
                                        </span>
                                    </td>
                                    <td class="subtotal">
                                        <?php echo app_format_money($estimate->subtotal, $estimate->currency_name); ?>
                                    </td>
                                </tr>
                                <?php if (is_sale_discount_applied($estimate)) { ?>
                                <tr>
                                    <td>
                                        <span class="bold tw-text-neutral-700"><?php echo _l('estimate_discount'); ?>
                                            <?php if (is_sale_discount($estimate, 'percent')) { ?>
                                            (<?php echo app_format_number($estimate->discount_percent, true); ?>%)
                                            <?php } ?>
                                        </span>
                                    </td>
                                    <td class="discount">
                                        <?php echo '-' . app_format_money($estimate->discount_total, $estimate->currency_name); ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php
                                    foreach ($items->taxes() as $tax) {
                                        echo '<tr class="tax-area"><td class="bold !tw-text-neutral-700">' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)</td><td>' . app_format_money($tax['total_tax'], $estimate->currency_name) . '</td></tr>';
                                    }
                                ?>
                                <?php if ((int)$estimate->adjustment != 0) { ?>
                                <tr>
                                    <td>
                                        <span class="bold tw-text-neutral-700">
                                            <?php echo _l('estimate_adjustment'); ?>
                                        </span>
                                    </td>
                                    <td class="adjustment">
                                        <?php echo app_format_money($estimate->adjustment, $estimate->currency_name); ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td>
                                        <span class="bold tw-text-neutral-700">
                                            <?php echo _l('estimate_total'); ?>
                                        </span>
                                    </td>
                                    <td class="total">
                                        <?php echo app_format_money($estimate->total, $estimate->currency_name); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
               if (get_option('total_to_words_enabled') == 1) { ?>
                    <div class="col-md-12 text-center estimate-html-total-to-words">
                        <p class="tw-font-medium">
                            <?php echo _l('num_word'); ?>:<span class="tw-text-neutral-500">
                                <?php echo $this->numberword->convert($estimate->total, $estimate->currency_name); ?>
                            </span>
                        </p>
                    </div>
                    <?php } ?>
                    <?php if (count($estimate->attachments) > 0 && $estimate->visible_attachments_to_customer_found == true) { ?>
                    <div class="clearfix"></div>
                    <div class="estimate-html-files">
                        <div class="col-md-12">
                            <hr />
                            <p class="bold mbot15 font-medium"><?php echo _l('estimate_files'); ?></p>
                        </div>
                        <?php foreach ($estimate->attachments as $attachment) {
                   // Do not show hidden attachments to customer
                   if ($attachment['visible_to_customer'] == 0) {
                       continue;
                   }
                   $attachment_url = site_url('download/file/sales_attachment/' . $attachment['attachment_key']);
                   if (!empty($attachment['external'])) {
                       $attachment_url = $attachment['external_link'];
                   } ?>
                        <div class="col-md-12 mbot15">
                            <div class="pull-left"><i
                                    class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                            </div>
                            <a href="<?php echo $attachment_url; ?>"><?php echo $attachment['file_name']; ?></a>
                        </div>
                        <?php
               } ?>
                    </div>
                    <?php } ?>
                    <?php if (!empty($estimate->clientnote)) { ?>
                    <div class="col-md-12 estimate-html-note">
                        <p class="tw-mb-2.5 tw-font-medium">
                            <b><?php echo _l('estimate_note'); ?></b>
                        </p>
                        <div class="tw-text-neutral-500">
                            <?php echo $estimate->clientnote; ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if (!empty($estimate->terms)) { ?>
                    <div class="col-md-12 estimate-html-terms-and-conditions">
                        <hr />
                        <p class="tw-mb-2.5 tw-font-medium">
                            <b><?php echo _l('terms_and_conditions'); ?></b>
                        </p>
                        <div class="tw-text-neutral-500">
                            <?php echo $estimate->terms; ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
   if ($identity_confirmation_enabled == '1' && $can_be_accepted) {
       get_template_part('identity_confirmation_form', ['formData' => form_hidden('estimate_action', 4)]);
   }
   ?>
    <script>
    $(function() {
        new Sticky('[data-sticky]');
    })
    </script>