<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('projects_chart'); ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body padding-10">
                    <div class="widget-dragger"></div>

                    <p
                        class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse tw-p-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                        </svg>

                        <span class="tw-text-neutral-700">
                            <?php echo _l('home_stats_by_project_status'); ?>
                        </span>
                    </p>

                    <hr class="-tw-mx-3 tw-mt-3 tw-mb-6">

                    <div class="relative" style="height:250px">
                        <canvas class="chart" height="250" id="projects_status_stats"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>