<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget<?php if (count($upcoming_events) == 0 || !is_staff_member()) {
    echo ' hide';
} ?>" id="widget-<?php echo create_widget_id(); ?>">
    <?php if (count($upcoming_events) > 0 && is_staff_member()) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s events">
                <div class="panel-body padding-10">
                    <div class="widget-dragger"></div>

                    <p class="tw-font-medium tw-flex tw-items-center tw-justify-between tw-mb-0 tw-p-1.5">
                        <span class="tw-flex tw-items-center tw-space-x-1.5 rtl:tw-space-x-reverse">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                            </svg>

                            <span class="tw-text-neutral-700">
                                <?php echo _l('home_this_week_events'); ?>
                            </span>
                        </span>

                        <span class="tw-my-0 tw-px-3 tw-text-sm tw-mt-0.5">
                            <span class="tw-text-neutral-500">
                                <?php echo _l('home_upcoming_events_next_week'); ?>:
                            </span> <span class="text-neutral-900">
                                <?php echo $upcoming_events_next_week; ?>
                            </span>
                        </span>
                    </p>

                    <hr class="-tw-mx-3 tw-mt-3 tw-mb-6">

                    <ol
                        class="tw-divide-y tw-divide-neutral-100 tw-text-sm tw-leading-6 lg:tw-col-span-7 xl:tw-col-span-8 tw-mb-0">
                        <?php foreach ($upcoming_events as $event) { ?>
                        <li class="tw-relative tw-flex tw-space-x-4 tw-px-2 xl:tw-static">
                            <a href="<?php echo admin_url('profile/' . $event['userid']); ?>">
                                <?php echo staff_profile_image($event['userid'], ['staff-profile-xs-image', 'flex-none']); ?>
                            </a>
                            <div class="tw-flex-auto">
                                <h3
                                    class="tw-pr-10 tw-m-0 tw-text-base tw-font-semibold tw-text-neutral-900 xl:tw-pr-0">
                                    <a href="#" onclick="view_event(<?php echo $event['eventid']; ?>); return false;">
                                        <?php echo $event['title']; ?>
                                    </a>
                                </h3>

                                <p class="text-muted no-margin"><?php echo $event['description']; ?></p>

                                <dl class="tw-mt-2 tw-flex tw-flex-col tw-text-neutral-500 xl:tw-flex-row">
                                    <div class="tw-flex tw-items-center tw-space-x-3">
                                        <dt class="tw-mt-0.5">
                                            <span class="tw-sr-only">Date</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="tw-h-5 tw-w-5 tw-text-neutral-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                        </dt>
                                        <dd>
                                            <time datetime="<?php echo $event['start']; ?>">
                                                <?php echo _dt($event['start']); ?>
                                            </time>
                                        </dd>
                                    </div>
                                    <div
                                        class="tw-mt-2 tw-flex tw-items-start tw-space-x-3 xl:tw-mt-0 xl:tw-ml-3.5 xl:tw-border-solid xl:tw-border-l xl:tw-border-neutral-400 xl:tw-border-opacity-50 xl:tw-pl-3.5">
                                        <?php if ($event['public'] == 1) { ?>
                                        <span class="text-success">
                                            <?php echo _l('home_public_event'); ?>
                                        </span>
                                        <?php } ?>
                                    </div>
                                    <?php if ($event['userid'] != get_staff_user_id()) { ?>
                                    <div
                                        class="tw-mt-2 tw-flex tw-items-start tw-space-x-3 xl:tw-mt-0 xl:tw-ml-3.5 xl:tw-border-solid xl:tw-border-l xl:tw-border-neutral-400 xl:tw-border-opacity-50 xl:tw-pl-3.5">
                                        <span class="tw-text-neutral-500">
                                            <?php echo _l('home_event_added_by'); ?><?php echo get_staff_full_name($event['userid']); ?>
                                        </span>
                                    </div>
                                    <?php } ?>
                                </dl>
                            </div>
                        </li>
                        <?php } ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>