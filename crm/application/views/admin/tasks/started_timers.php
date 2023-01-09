<?php

defined('BASEPATH') or exit('No direct script access allowed');

$totalTimers         = count($startedTimers);
$noTimersWithoutTask = true;
if ($totalTimers == 0) {
    echo '<li class="text-center inline-block full-width tw-mb-2"><div class="text-center">
    <svg class="tw-mx-auto tw-h-10 tw-w-10 tw-text-neutral-400 -tw-mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
      <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
    </svg>
    <p class="tw-my-0 tw-text-sm tw-font-medium tw-text-gray-900">' . _l('no_timers_found') . '</p>
  </div></li>';
}

$i = 0;
foreach ($startedTimers as $timer) {
    $data = '';

    $data .= '<li class="timer relative" id="timer-' . $timer['id'] . '">';

    if ($timer['task_id'] != '0') {
        $data .= '<a href="' . admin_url('tasks/view/' . $timer['task_id']) . '" class="_timer font-medium" onclick="init_task_modal(' . $timer['task_id'] . ');return false;">' . $timer['task_subject'] . '</a>';
    } else {
        $noTimersWithoutTask = false;
    }

    $data .= '<span class="pointer pull-right unfinished-timesheet-delete" onclick="delete_user_unfinished_timesheet(' . $timer['id'] . '); return false;"><i class="fa-regular fa-trash-can"></i></span>';

    $data .= '<span class="tw-text-neutral-600">' . _l('timer_top_started', _dt($timer['start_time'], true)) . '</span><br /><span class="text-success tw-text-sm">' . _l('task_total_logged_time') . ' ' . seconds_to_time_format($this->tasks_model->calc_task_total_time($timer['task_id'], ' AND staff_id=' . get_staff_user_id())) . '</span>';

    $data .= '<p class="mtop10"><a href="#" class="label label-danger top-stop-timer" ';

    if ($timer['task_id'] != '0') {
        $data .= 'data-toggle="popover" data-html="true" data-trigger="manual" data-title="' . _l('task_stop_timer') . '"';

        $data .= 'data-container="body" data-template="<div class=\'popover popover-top-timer-note\' role=\'tooltip\'><div class=\'arrow\'></div><h3 class=\'popover-title\'></h3><div class=\'popover-content\'></div></div>" ';

        $data .= 'data-content="';
        $data .= htmlspecialchars(render_textarea('timesheet_note'));
        $data .= '<button type=\'button\' onclick=\'timer_action(this,' . $timer['task_id'] . ',' . $timer['id'] . ');\' class=\'btn btn-primary btn-sm\'>' . _l('save') . '</button>" ';
        $data .= 'onclick="return false;">';
    } else {
        $data .= 'onclick=\'timer_action(this,' . $timer['task_id'] . ',' . $timer['id'] . '); return false;\' data-timer-id=\'' . $timer['id'] . '\'>';
    }

    $data .= '<i class="fa-regular fa-clock tw-mr-1"></i> ' . _l('task_stop_timer') . '</a>';
    $data .= '</p>';
    $data .= '</li>';
    if ($i >= 0 && $i != $totalTimers - 1) {
        $data .= '<hr />';
    }
    echo $data;
    $i++;
}
// You can't start multiple blank timers
if ($noTimersWithoutTask
    && !(get_option('auto_stop_tasks_timers_on_new_timer') == 1
        && total_rows(db_prefix() . 'taskstimers', 'staff_id=' . get_staff_user_id() . ' AND end_time IS NULL') > 0)
    ) {
    echo '<button class="tw-mt-3 text-center btn btn-primary started-timers-button  btn-sm top-dropdown-btn" onclick="timer_action(this,0); return false;"><i class="fa-regular fa-clock"></i> ' . _l('task_start_timer') . '</button>';
}

if (is_admin()) {
    echo '<div class="text-center tw-mt-1 view-all-timesheets">';
    echo '<a href="' . admin_url('staff/timesheets?view=all') . '" class="tw-text-sm">' . _l('view_members_timesheets') . '</a>';
    echo '</div>';
}