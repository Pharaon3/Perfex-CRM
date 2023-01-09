<?php defined('BASEPATH') or exit('No direct script access allowed');

foreach ($milestones as $milestone) {
    $milestonesTasksWhere = [];
    if ($milestones_exclude_completed_tasks) {
        $milestonesTasksWhere['status !='] = Tasks_model::STATUS_COMPLETE;
    }
    $cpicker = '';
    if (staff_can('edit_milestones', 'projects') && $milestone['id'] != 0) {
        foreach (get_system_favourite_colors() as $color) {
            $color_selected_class = 'cpicker-small';
            $cpicker .= "<div class='kanban-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ';border:1px solid ' . $color . "'></div>";
        }
    }

    $milestone_color = '';

    if (!empty($milestone['color']) && !is_null($milestone['color'])) {
        $milestone_color = ' style="background:' . $milestone['color'] . ';border:1px solid ' . $milestone['color'] . '"';
    }

    $total_pages = ceil($this->projects_model->do_milestones_kanban_query($milestone['id'], $project_id, 1, $milestonesTasksWhere, true) / get_option('tasks_kanban_limit'));

    $tasks       = $this->projects_model->do_milestones_kanban_query($milestone['id'], $project_id, 1, $milestonesTasksWhere);
    $total_tasks = count($tasks);

    if ($milestone['id'] == 0 && count($tasks) == 0) {
        continue;
    } ?>
<ul class="kan-ban-col milestone-column<?php if (!staff_can('edit_milestones', 'projects') || $milestone['id'] == 0) {
        echo ' milestone-not-sortable';
    }; ?>" data-col-status-id="<?php echo $milestone['id']; ?>" data-total-pages="<?php echo $total_pages; ?>">
    <li class="kan-ban-col-wrapper">
        <div class="border-right panel_s">
            <div class="panel-heading <?php if ($milestone_color != '') {
        echo 'color-not-auto-adjusted color-white ';
    } ?><?php if ($milestone['id'] != 0) {
        echo 'task-phase';
    } else {
        echo 'bg-info';
    } ?>" <?php echo $milestone_color; ?>>
                <?php if ($milestone['id'] != 0 && staff_can('edit_milestones', 'projects')) { ?>
                <i class="fa fa-reorder pointer"></i>&nbsp;
                <?php } ?>
                <?php if ($milestone['id'] != 0 && staff_can('edit_milestones', 'projects')) { ?>
                <a href="#" data-hide-from-customer="<?php echo $milestone['hide_from_customer']; ?>"
                    data-description-visible-to-customer="<?php echo $milestone['description_visible_to_customer']; ?>"
                    data-description="<?php echo $milestone['description'] ? htmlspecialchars(clear_textarea_breaks($milestone['description'])) : ''; ?>"
                    data-name="<?php echo $milestone['name']; ?>"
                    data-start_date="<?php echo $milestone['id'] != 0 ? _d($milestone['start_date']) : ''; ?>"
                    data-due_date="<?php echo $milestone['id'] != 0 ? _d($milestone['due_date']) : ''; ?>"
                    data-order="<?php echo $milestone['milestone_order']; ?>"
                    onclick="edit_milestone(this,<?php echo $milestone['id']; ?>); return false;" class="edit-milestone-phase <?php if ($milestone['color'] != '') {
        echo 'color-white';
    } ?>">
                    <?php } ?>
                    <span class="bold heading"><?php echo $milestone['name']; ?></span>
                    <span class="tw-text-sm">
                        <?php echo  $milestone['id'] != 0 ? (' | ' . _d($milestone['start_date']) . ' - ' . _d($milestone['due_date'])) : ''; ?>
                    </span>
                    <?php if ($milestone['id'] != 0 && staff_can('edit_milestones', 'projects')) { ?>
                </a>
                <?php } ?>
                </span>
                <?php if ($milestone['id'] != 0 && (staff_can('create', 'tasks') || staff_can('edit_milestones', 'projects'))) { ?>
                <a href="#" onclick="return false;" class="pull-right text-dark" data-placement="bottom"
                    data-toggle="popover" data-content="
      <div class='text-center'><?php if (staff_can('create', 'tasks')) {
        ?><button type='button' return false;' class='btn btn-success btn-block mtop10 new-task-to-milestone'>
         <?php echo _l('new_task'); ?>
       </button>
     <?php
    } ?>
   </div>
   <?php if (staff_can('edit_milestones', 'projects')) { ?>
   <?php if ($cpicker != '') {
        echo '<hr />';
    }; ?>
   <div class='kan-ban-settings cpicker-wrapper'>
     <?php echo $cpicker; ?>
   </div>
   <a href='#' class='reset_milestone_color <?php if ($milestone_color == '') {
        echo 'hide';
    } ?>' data-color=''>
     <?php echo _l('reset_to_default_color'); ?>
   </a><?php } ?>" data-html="true" data-trigger="focus">
                    <i class="fa fa-angle-down"></i>
                </a>
                <?php } ?>
                <?php if (staff_can('create', 'tasks')) { ?>
                <?php echo '<p class="tw-ml-5 tw-text-sm tw-mb-0">' . _l('milestone_total_logged_time') . ': ' . seconds_to_time_format($milestone['total_logged_time']) . '</p>'; } ?>
            </div>
            <div class="kan-ban-content-wrapper">
                <div class="kan-ban-content">
                    <ul class="status project-milestone milestone-tasks-wrapper sortable relative"
                        data-task-status-id="<?php echo $milestone['id']; ?>">
                        <?php
    foreach ($tasks as $task) {
        $this->load->view('admin/projects/_milestone_kanban_card', ['task' => $task, 'milestone' => $milestone['id']]);
    } ?>
                        <?php if ($total_tasks > 0) { ?>
                        <li class="text-center not-sortable kanban-load-more"
                            data-load-status="<?php echo $milestone['id']; ?>">
                            <a href="#" class="btn btn-default btn-block<?php if ($total_pages <= 1) {
        echo ' disabled';
    } ?>" data-page="1" onclick="kanban_load_more(<?php echo $milestone['id']; ?>,this,'projects/milestones_kanban_load_more',320,360); return false;"
                                ;>
                                <?php echo _l('load_more'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="text-center not-sortable mtop30 kanban-empty<?php if ($total_tasks > 0) {
        echo ' hide';
    } ?>">
                            <h4>
                                <i class="fa-solid fa-circle-notch" aria-hidden="true"></i><br /><br />
                                <?php echo _l('no_tasks_found'); ?>
                            </h4>
                        </li>
                    </ul>
                </div>
            </div>
    </li>
</ul>
<?php
} ?>