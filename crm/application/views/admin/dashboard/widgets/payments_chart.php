<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('home_payment_records'); ?>">
    <?php if (has_permission('payments', '', 'view') || has_permission('invoices', '', 'view_own')) { ?>
    <div class="row" id="payments">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body padding-10">
                    <div class="widget-dragger"></div>

                    <div class="tw-flex tw-justify-between tw-items-center tw-p-1.5">
                        <p class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />
                            </svg>

                            <span class="tw-text-neutral-700">
                                <?php echo _l('home_payment_records'); ?>
                            </span>
                        </p>
                        <div
                            class="tw-divide-x tw-divide-solid tw-divide-neutral-300 tw-space-x-2 tw-flex tw-items-center">

                            <?php if (is_using_multiple_currencies()) { ?>
                            <select class="selectpicker tw-pr-2" name="currency"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <?php foreach ($currencies as $currency) {
    $selected = '';
    if ($currency['isdefault'] == 1) {
        $selected = 'selected';
    } ?>
                                <option value="<?php echo $currency['id']; ?>" <?php echo $selected; ?>
                                    data-subtext="<?php echo $currency['name']; ?>"><?php echo $currency['symbol']; ?>
                                </option>
                                <?php
} ?>
                            </select>
                            <?php } ?>
                            <?php if (has_permission('reports', '', 'view')) { ?>
                            <a href="<?php echo admin_url('reports/sales'); ?>" class="tw-pl-2">
                                <?php echo _l('home_stats_full_report'); ?>
                            </a>
                            <?php } ?>
                            <div class="dropdown pull-right mright10">
                                <a href="#" id="PaymentChartmode" class="dropdown-toggle tw-pl-2" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span id="Payment-chart-name" data-active-chart="weekly">
                                        <?php echo _l('weekly') ?>
                                    </span>
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="PaymentChartmode">
                                    <li>
                                        <a href="#" data-type="weekly"
                                            onclick="update_payment_statistics(this); return false;">
                                            <?php echo _l('weekly') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-type="monthly"
                                            onclick="update_payment_statistics(this); return false;">
                                            <?php echo _l('monthly') ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <hr class="-tw-mx-3 tw-mt-2 tw-mb-4">

                    <canvas height="130" class="payments-chart-dashboard" id="payment-statistics"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>