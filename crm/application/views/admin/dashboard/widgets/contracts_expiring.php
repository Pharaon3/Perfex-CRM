<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>"
    data-name="<?php echo _l('contracts_about_to_expire'); ?>">
    <?php if (staff_can('view', 'contracts') || staff_can('view_own', 'contracts')) { ?>
    <div class="panel_s contracts-expiring">
        <div class="panel-body padding-10">
            <div class="widget-dragger"></div>
            <div class="tw-flex tw-justify-between tw-items-center tw-p-1.5">
                <p class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <span class="tw-text-neutral-700">
                        <?php echo _l('contracts_about_to_expire'); ?>
                    </span>
                </p>
                <a href="<?php echo admin_url('contracts'); ?>">
                    <?php echo _l('home_widget_view_all'); ?>
                </a>
            </div>

            <hr class="-tw-mx-3 tw-mt-2 tw-mb-4">


            <?php if (!empty($expiringContracts)) { ?>
            <div class="tw-overflow-y-auto tw-overflow-x-hidden tw-h-[420px] tw-p-2">

                <table class="table dt-table" data-order-col="3" data-order-type="desc">
                    <thead>
                        <tr>
                            <th><?php echo _l('contract_list_subject'); ?> #</th>
                            <th class="<?php echo(isset($client) ? 'not_visible' : ''); ?>">
                                <?php echo _l('contract_list_client'); ?></th>
                            <th><?php echo _l('contract_list_start_date'); ?></th>
                            <th><?php echo _l('contract_list_end_date'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expiringContracts as $contract) { ?>
                        <tr>
                            <td>
                                <?php echo '<a href="' . admin_url('contracts/contract/' . $contract['id']) . '">' . $contract['subject'] . '</a>'; ?>
                            </td>
                            <td>
                                <?php echo '<a href="' . admin_url('clients/client/' . $contract['client']) . '">' . get_company_name($contract['client']) . '</a>'; ?>
                            </td>
                            <td>
                                <?php echo _d($contract['datestart']); ?>
                            </td>
                            <td>
                                <?php echo _d($contract['dateend']); ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } else { ?>
            <div class="text-center padding-5">
                <i class="fa fa-check fa-5x" aria-hidden="true"></i>
                <h4><?php echo _l('no_contracts_about_to_expire', ['7']) ; ?> </h4>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>