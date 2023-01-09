<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$canViewInvoices  = (staff_can('view', 'invoices') || staff_can('view_own', 'invoices') || (get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices()));
$canViewProposals = (staff_can('view', 'proposals') || staff_can('view_own', 'proposals') || (get_option('allow_staff_view_proposals_assigned') == 1 && staff_has_assigned_proposals()));
$canViewEstimates = (staff_can('view', 'estimates') || staff_can('view_own', 'estimates') || (get_option('allow_staff_view_estimates_assigned') == 1 && staff_has_assigned_estimates()));

switch (count(array_filter([$canViewInvoices, $canViewEstimates, $canViewProposals]))) {
   case 3:
   $totalColumnsLg = 4;

   break;
   case 2:
   $totalColumnsLg = 6;

   break;
   case 1:
   $totalColumnsLg = 12;

   break;
   default:
   $totalColumnsLg = 0;

   break;
}
?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('finance_overview'); ?>">
    <?php if ($canViewInvoices || $canViewEstimates || $canViewProposals) { ?>
    <div class="finance-summary">
        <div class="panel_s">
            <div class="panel-body padding-15">
                <div class="widget-dragger"></div>
                <div class="row home-summary">
                    <?php if ($canViewInvoices) { ?>
                    <div class="col-md-6 col-lg-<?php echo $totalColumnsLg; ?> col-sm-6">
                        <div class="row">
                            <div class="col-md-12">
                                <p
                                    class="text-neutral-700 tw-mb-8 tw-inline-flex tw-items-center tw-space-x-1.5 rtl:tw-space-x-reverse -tw-mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="tw-6 tw-h-6 tw-text-neutral-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>

                                    <span class="tw-font-medium">
                                        <?php echo _l('home_invoice_overview'); ?>
                                    </span>
                                </p>

                            </div>
                            <?php $percent_data = get_invoices_percent_by_status(6); ?>
                            <div class="col-md-12 text-stats-wrapper">
                                <a href="<?php echo admin_url('invoices/list_invoices?status=6'); ?>"
                                    class="text-muted mbot15 inline-block">
                                    <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span>
                                    <?php echo format_invoice_status(6, '', false); ?>
                                </a>
                            </div>
                            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                                <?php echo $percent_data['percent']; ?>%
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar progress-bar-default no-percent-text not-dynamic"
                                        role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $percent_data['percent']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php $percent_data = get_invoices_percent_by_status('not_sent'); ?>
                            <div class="col-md-12 text-stats-wrapper">
                                <a href="<?php echo admin_url('invoices/list_invoices?filter=not_sent'); ?>"
                                    class="text-muted inline-block mbot15">
                                    <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span>
                                    <?php echo _l('not_sent_indicator'); ?>
                                </a>
                            </div>
                            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                                <?php echo $percent_data['percent']; ?>%
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar progress-bar-default no-percent-text not-dynamic"
                                        role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $percent_data['percent']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php $percent_data = get_invoices_percent_by_status(1); ?>
                            <div class="col-md-12 text-stats-wrapper">
                                <a href="<?php echo admin_url('invoices/list_invoices?status=1'); ?>"
                                    class="text-danger mbot15 inline-block">
                                    <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span>
                                    <?php echo format_invoice_status(1, '', false); ?>
                                </a>
                            </div>
                            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                                <?php echo $percent_data['percent']; ?>%
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar progress-bar-danger no-percent-text not-dynamic"
                                        role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $percent_data['percent']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php $percent_data = get_invoices_percent_by_status(3); ?>
                            <div class="col-md-12 text-stats-wrapper">
                                <a href="<?php echo admin_url('invoices/list_invoices?status=3'); ?>"
                                    class="text-warning mbot15 inline-block">
                                    <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span>
                                    <?php echo format_invoice_status(3, '', false); ?>
                                </a>
                            </div>
                            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                                <?php echo $percent_data['percent']; ?>%
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar progress-bar-danger no-percent-text not-dynamic"
                                        role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $percent_data['percent']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php $percent_data = get_invoices_percent_by_status(4); ?>
                            <div class="col-md-12 text-stats-wrapper">
                                <a href="<?php echo admin_url('invoices/list_invoices?status=4'); ?>"
                                    class="text-warning mbot15 inline-block">
                                    <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span>
                                    <?php echo format_invoice_status(4, '', false); ?>
                                </a>
                            </div>
                            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                                <?php echo $percent_data['percent']; ?>%
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar progress-bar-warning no-percent-text not-dynamic"
                                        role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $percent_data['percent']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php $percent_data = get_invoices_percent_by_status(2); ?>
                            <div class="col-md-12 text-stats-wrapper">
                                <a href="<?php echo admin_url('invoices/list_invoices?status=2'); ?>"
                                    class="text-success mbot15 inline-block">
                                    <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span>
                                    <?php echo format_invoice_status(2, '', false); ?>
                                </a>
                            </div>
                            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                                <?php echo $percent_data['percent']; ?>%
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar progress-bar-success no-percent-text not-dynamic"
                                        role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $percent_data['percent']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if ($canViewEstimates) { ?>
                    <div class="col-md-6 col-lg-<?php echo $totalColumnsLg; ?> col-sm-6">
                        <div class="row">
                            <div class="col-md-12 text-stats-wrapper">
                                <p
                                    class="text-neutral-700 tw-mb-8 tw-inline-flex tw-items-center tw-space-x-1.5 rtl:tw-space-x-reverse -tw-mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="tw-6 tw-h-6 tw-text-neutral-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <span class="tw-font-medium">
                                        <?php echo _l('home_estimate_overview'); ?>
                                    </span>
                                </p>

                            </div>
                            <?php
                           // Add not sent
                        array_splice($estimate_statuses, 1, 0, 'not_sent');
                        foreach ($estimate_statuses as $status) {
                            $url = admin_url('estimates/list_estimates?status=' . $status);
                            if (!is_numeric($status)) {
                                $url = admin_url('estimates/list_estimates?filter=' . $status);
                            }
                            $percent_data = get_estimates_percent_by_status($status); ?>
                            <div class="col-md-12 text-stats-wrapper">
                                <a href="<?php echo $url; ?>"
                                    class="text-<?php echo estimate_status_color_class($status, true); ?> mbot15 inline-block estimate-status-dashboard-<?php echo estimate_status_color_class($status, true); ?>">
                                    <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span>
                                    <?php echo format_estimate_status($status, '', false); ?>
                                </a>
                            </div>
                            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                                <?php echo $percent_data['percent']; ?>%
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar progress-bar-<?php echo estimate_status_color_class($status); ?> no-percent-text not-dynamic"
                                        role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $percent_data['percent']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php
                        } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if ($canViewProposals) { ?>
                    <div class="col-md-12 col-sm-6 col-lg-<?php echo $totalColumnsLg; ?>">
                        <div class="row">
                            <div class="col-md-12 text-stats-wrapper">
                                <p
                                    class="text-neutral-700 tw-mb-8 tw-inline-flex tw-items-center tw-space-x-1.5 rtl:tw-space-x-reverse -tw-mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="tw-6 tw-h-6 tw-text-neutral-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <span class="tw-font-medium">
                                        <?php echo _l('home_proposal_overview'); ?>
                                    </span>
                                </p>

                            </div>
                            <?php foreach ($proposal_statuses as $status) {
                            $url          = admin_url('proposals/list_proposals?status=' . $status);
                            $percent_data = get_proposals_percent_by_status($status); ?>
                            <div class="col-md-12 text-stats-wrapper">
                                <a href="<?php echo $url; ?>"
                                    class="text-<?php echo proposal_status_color_class($status, true); ?> mbot15 inline-block">
                                    <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span>
                                    <?php echo format_proposal_status($status, '', false); ?>
                                </a>
                            </div>
                            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                                <?php echo $percent_data['percent']; ?>%
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar progress-bar-<?php echo proposal_status_color_class($status); ?> no-percent-text not-dynamic"
                                        role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $percent_data['percent']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php
                        } ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php if (has_permission('invoices', '', 'view') || has_permission('invoices', '', 'view_own')) { ?>
                <hr class="-tw-mx-8" />
                <a href="#" class="hide invoices-total initialized"></a>
                <div id="invoices_total" class="invoices-total-inline">
                    <?php load_invoices_total_template(); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>