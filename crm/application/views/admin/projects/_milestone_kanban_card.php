<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<li data-task-id="<?php echo $task['id']; ?>" class="task<?php if (has_permission('tasks', '', 'create') || has_permission('tasks', '', 'edit')) {
    echo ' sortable';
} ?><?php if ($task['current_user_is_assigned']) {
    echo ' current-user-task';
} if ((!empty($task['duedate']) && $task['duedate'] < date('Y-m-d')) && $task['status'] != Tasks_model::STATUS_COMPLETE) {
    echo ' overdue-task';
} ?>">
    <div class="panel-body">
        <div class="sm:tw-flex">
            <?php
$assignees = explode(',', $task['assignees_ids']);
if (count($assignees) > 0 && $assignees[0] != '') { ?>
            <div
                class="tw-mb-4 tw-flex-shrink-0 sm:tw-mb-0 sm:tw-mr-2 tw-flex tw-flex-col tw-items-center tw-space-y-0.5">
                <?php if ($task['current_user_is_assigned']) {
    echo staff_profile_image(get_staff_user_id(), ['staff-profile-image-small'], 'small', ['data-toggle' => 'tooltip', 'data-title' => _l('project_task_assigned_to_user')]);
}
                foreach ($assignees as $assigned) {
                    $assigned = trim($assigned);
                    if ($assigned != get_staff_user_id()) {
                        echo staff_profile_image($assigned, ['staff-profile-image-xs sub-staff-assigned-milestone'], 'small', ['data-toggle' => 'tooltip', 'data-title' => get_staff_full_name($assigned)]);
                    }
                }
            ?>
            </div>
            <?php } ?>
            <div>
                <h4 class="tw-text-base tw-my-0">
                    <a href="<?php echo admin_url('tasks/view/' . $task['id']); ?>"
                        class="task_milestone tw-text-neutral-600 hover:tw-text-neutral-700 active:tw-text-neutral-700<?php echo $task['status'] == Tasks_model::STATUS_COMPLETE ? ' text-muted line-through' : ''; ?>"
                        onclick="init_task_modal(<?php echo $task['id']; ?>); return false;">
                        <?php echo $task['name']; ?>
                    </a>
                </h4>
                <p class="tw-mb-0 tw-mt-1 tw-text-sm">
                    <?php echo format_task_status($task['status'], true); ?>
                </p>
                <?php if (has_permission('tasks', '', 'create')) { ?>
                <p class="tw-mb-0 tw-text-sm tw-font-medium">
                    <?php echo _l('task_total_logged_time'); ?>
                    <?php echo seconds_to_time_format($task['total_logged_time']); ?>
                </p>
                <?php } ?>
                <p class="tw-mb-0 tw-text-sm tw-font-medium">
                    <?php echo _d($task['startdate']); ?>
                    <?php if (is_date($task['duedate'])) { ?>
                    -
                    <?php echo _d($task['duedate']); ?>
                    <?php } ?>
                </p>
            </div>
        </div>
    </div>
</li>