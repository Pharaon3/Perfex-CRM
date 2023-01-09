<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tickets-summary-heading">
    <?php echo _l('tickets_summary'); ?>
</h4>

<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-2 sm:tw-gap-4 tw-mt-2 tw-mb-10">
    <?php foreach (get_clients_area_tickets_summary($ticket_statuses) as $status) { ?>
    <a href="<?php echo $status['url']; ?>"
        class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md hover:tw-bg-neutral-100 <?php echo in_array($status['ticketstatusid'], $list_statuses) ? 'tw-bg-white' : 'tw-bg-neutral-50 '; ?>">
        <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
            <dt class="tw-font-medium" style="color:<?php echo $status['statuscolor']; ?>">
                <?php echo $status['translated_name']; ?>
            </dt>
            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                    <?php echo $status['total_tickets'] ?>
                </div>
            </dd>
        </div>
    </a>
    <?php } ?>
</dl>

<div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-tickets">
        <?php echo _l('clients_tickets_heading'); ?>
    </h4>
    <a href="<?php echo site_url('clients/open_ticket'); ?>" class="btn btn-primary new-ticket">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('clients_ticket_open_subject'); ?>
    </a>
</div>

<div class="panel_s">
    <div class="panel-body">
        <?php get_template_part('tickets_table'); ?>
    </div>
</div>