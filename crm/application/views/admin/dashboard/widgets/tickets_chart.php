<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>">
    <?php if ((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member() || is_staff_member()) && (count($tickets_reply_by_status_no_json['datasets'][0]['data']) > 0 || count($tickets_awaiting_reply_by_department_no_json['datasets'][0]['data']) > 0)) { ?>
    <div class="panel_s">
        <div class="panel-body padding-10">
            <div class="widget-dragger"></div>
            <div class="row">
                <div class="col-md-12 mbot10">
                    <p
                        class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse tw-p-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                        </svg>
                        <span class="tw-text-neutral-700">
                            <?php echo _l('home_tickets_awaiting_reply_by_status'); ?>
                        </span>
                    </p>

                    <hr class="-tw-mx-3 tw-mt-3 tw-mb-6">

                    <canvas height="170" id="tickets-awaiting-reply-by-status"></canvas>
                </div>
                <div class="clearfix"></div>
                <hr class="no-margin" />
                <div class="clearfix mtop10"></div>
                <div class="col-md-12">
                    <p class="padding-5 tw-font-semibold">
                        <?php echo _l('home_tickets_awaiting_reply_by_department'); ?>
                    </p>
                    <hr class="-tw-mx-2.5 tw-mt-0">
                    <canvas height="170" id="tickets-awaiting-reply-by-department"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>