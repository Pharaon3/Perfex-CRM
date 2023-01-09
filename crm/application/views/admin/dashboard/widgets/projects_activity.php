<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget<?php if (count($projects_activity) == 0) {
    echo ' hide';
} ?>" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('home_project_activity'); ?>">
    <div class="panel_s projects-activity">
        <div class="panel-body padding-10">
            <div class="widget-dragger"></div>
            <p class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse tw-p-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>


                <span class="tw-text-neutral-700">
                    <?php echo _l('home_project_activity'); ?>
                </span>
            </p>

            <hr class="-tw-mx-3 tw-mt-3 tw-mb-6">


            <div class="activity-feed">
                <?php
     foreach ($projects_activity as $activity) {
         $name = $activity['fullname'];
         if ($activity['staff_id'] != 0) {
             $href = admin_url('profile/' . $activity['staff_id']);
         } elseif ($activity['contact_id'] != 0) {
             $name = '<span class="label label-info inline-block mright5">' . _l('is_customer_indicator') . '</span> - ' . $name;
             $href = admin_url('clients/client/' . get_user_id_by_contact_id($activity['contact_id']) . '?contactid=' . $activity['contact_id']);
         } else {
             $href = '';
             $name = '[CRON]';
         } ?>
                <div class="feed-item">
                    <div class="date"><span class="text-has-action" data-toggle="tooltip"
                            data-title="<?php echo _dt($activity['dateadded']); ?>">
                            <?php echo time_ago($activity['dateadded']); ?>
                        </span>
                    </div>
                    <div class="text">
                        <p class="bold no-mbot">
                            <?php if ($href != '') { ?>
                            <a href="<?php echo $href; ?>"><?php echo $name; ?></a> -
                            <?php } else {
             echo $name;
         } ; ?>
                            <?php echo $activity['description']; ?>
                        </p>
                        <?php echo _l('project_name'); ?>: <a
                            href="<?php echo admin_url('projects/view/' . $activity['project_id']); ?>"><?php echo $activity['project_name']; ?></a>
                    </div>
                    <?php if (!empty($activity['additional_data'])) { ?>
                    <p class="text-muted mtop5"><?php echo $activity['additional_data']; ?></p>
                    <?php } ?>
                </div>
                <?php
     } ?>
            </div>
        </div>
    </div>
</div>