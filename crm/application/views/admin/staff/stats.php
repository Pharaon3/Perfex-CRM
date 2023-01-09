<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="staff_logged_time" data-toggle="tooltip" data-title="<?php echo _l('task_timesheets'); ?>"
    data-placement="top">
    <dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-3 sm:tw-gap-5">
        <div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium text-success">
                    <?php echo _l('staff_stats_total_logged_time'); ?>
                </dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <?php echo seconds_to_time_format($logged_time['total']); ?>
                    </div>
                </dd>
            </div>
        </div>

        <div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium text-info">
                    <?php echo _l('staff_stats_last_month_total_logged_time'); ?>
                </dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <?php echo seconds_to_time_format($logged_time['last_month']); ?>
                    </div>
                </dd>
            </div>
        </div>

        <div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium text-success">
                    <?php echo _l('staff_stats_this_month_total_logged_time'); ?>
                </dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <?php echo seconds_to_time_format($logged_time['this_month']); ?>
                    </div>
                </dd>
            </div>
        </div>

        <div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium text-info">
                    <?php echo _l('staff_stats_last_week_total_logged_time'); ?>
                </dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <?php echo seconds_to_time_format($logged_time['last_week']); ?>
                    </div>
                </dd>
            </div>
        </div>

        <div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium text-success">
                    <?php echo _l('staff_stats_this_week_total_logged_time'); ?>
                </dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <?php echo seconds_to_time_format($logged_time['this_week']); ?>
                    </div>
                </dd>
            </div>
        </div>
    </dl>
</div>