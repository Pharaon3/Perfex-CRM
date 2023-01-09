<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_hidden('project_id', $project->id); ?>
<div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <div class="tw-flex tw-items-center">
        <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-project">
            <?php echo $project->name; ?>
        </h4>
        <?php if ($project->settings->view_team_members == 1 && count($members) > 0) { ?>
        <div class="team-members tw-items-center ltr:tw-space-x-2 tw-ml-3 tw-inline-flex">
            <div class="tw-flex -tw-space-x-1">
                <?php foreach ($members as $member) { ?>
                <span data-title="<?php echo get_staff_full_name($member['staff_id']); ?>" data-toggle="tooltip">
                    <?php
                echo staff_profile_image(
    $member['staff_id'],
    ['tw-inline-block tw-h-7 tw-w-7 tw-rounded-full tw-ring-2 tw-ring-white', '']
);
                ?>
                </span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php
            echo '<span class="label project-status-' . $project_status['id'] . ' tw-ml-3" style="color:' . $project_status['color'] . ';border:1px solid ' . adjust_hex_brightness($project_status['color'], 0.4) . ';background: ' . adjust_hex_brightness($project_status['color'], 0.04) . ';">' . $project_status['name'] . '</span>';
       ?>
    </div>
    <?php if ($project->settings->view_tasks == 1 && $project->settings->create_tasks == 1) { ?>
    <a href="<?php echo site_url('clients/project/' . $project->id . '?group=new_task'); ?>"
        class="btn btn-primary new-task">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('new_task'); ?>
    </a>
    <?php } ?>
</div>
<div class="panel_s">
    <div class="panel-body">
        <?php get_template_part('projects/project_tabs'); ?>
        <div class="clearfix mtop15"></div>
        <?php get_template_part('projects/' . $group); ?>
    </div>
</div>