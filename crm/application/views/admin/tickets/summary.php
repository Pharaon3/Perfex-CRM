<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <?php
 $statuses = $this->tickets_model->get_ticket_status();
 ?>
    <div class="_filters _hidden_inputs hidden tickets_filters">
        <?php
  echo form_hidden('my_tickets');
  echo form_hidden('merged_tickets');
  if (is_admin()) {
      $ticket_assignees = $this->tickets_model->get_tickets_assignes_disctinct();
      foreach ($ticket_assignees as $assignee) {
          echo form_hidden('ticket_assignee_' . $assignee['assigned']);
      }
  }
  foreach ($statuses as $status) {
      $val = '';
      if ($chosen_ticket_status != '') {
          if ($chosen_ticket_status == $status['ticketstatusid']) {
              $val = $chosen_ticket_status;
          }
      } else {
          if (in_array($status['ticketstatusid'], $default_tickets_list_statuses)) {
              $val = 1;
          }
      }
      echo form_hidden('ticket_status_' . $status['ticketstatusid'], $val);
  } ?>
    </div>
    <div class="col-md-12">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>

            <span>
                <?php echo _l('tickets_summary'); ?>
            </span>
        </h4>
    </div>
    <?php
  $where = '';
  if (!is_admin()) {
      if (get_option('staff_access_only_assigned_departments') == 1) {
          $departments_ids = [];
          if (count($staff_deparments_ids) == 0) {
              $departments = $this->departments_model->get();
              foreach ($departments as $department) {
                  array_push($departments_ids, $department['departmentid']);
              }
          } else {
              $departments_ids = $staff_deparments_ids;
          }
          if (count($departments_ids) > 0) {
              $where = 'AND department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")';
          }
      }
  }
foreach ($statuses as $status) {
    $_where = '';
    if ($where == '') {
        $_where = 'status=' . $status['ticketstatusid'];
    } else {
        $_where = 'status=' . $status['ticketstatusid'] . ' ' . $where;
    }
    if (isset($project_id)) {
        $_where = $_where . ' AND project_id=' . $project_id;
    }
    $_where = $_where . ' AND merged_ticket_id IS NULL'; ?>
    <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0">
        <a href="#" data-cview="ticket_status_<?php echo $status['ticketstatusid']; ?>"
            class="tw-text-neutral-600 hover:tw-opacity-70 tw-inline-flex tw-items-center"
            onclick="dt_custom_view('ticket_status_<?php echo $status['ticketstatusid']; ?>','.tickets-table','ticket_status_<?php echo $status['ticketstatusid']; ?>',true); return false;">
            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                <?php echo total_rows(db_prefix() . 'tickets', $_where); ?>
            </span>
            <span style="color:<?php echo $status['statuscolor']; ?>">
                <?php echo ticket_status_translate($status['ticketstatusid']); ?>
            </span>
        </a>
    </div>
    <?php
} ?>
</div>