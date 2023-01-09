<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row mtop15">
    <div class="col-md-6">
        <h4 class="tw-font-semibold tw-text-base tw-mt-0 tw-mb-4">
            <?php echo _l('project_overview'); ?>
        </h4>
        <div class="tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project'); ?></p>
            <p><?php echo _l('the_number_sign'); ?><?php echo $project->id; ?></p>
        </div>
        <?php if ($project->settings->view_finance_overview == 1) { ?>
        <div class="project-billing-type tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project_billing_type'); ?></p>
            <p>
                <?php
              if ($project->billing_type == 1) {
                  $type_name = 'project_billing_type_fixed_cost';
              } elseif ($project->billing_type == 2) {
                  $type_name = 'project_billing_type_project_hours';
              } else {
                  $type_name = 'project_billing_type_project_task_hours';
              }
              echo _l($type_name);
          ?>
            </p>
        </div>
        <?php } ?>
        <?php if (($project->billing_type == 1 || $project->billing_type == 2) && $project->settings->view_finance_overview == 1) {
              echo '<div class="project-cost tw-flex tw-space-x-4">';
              if ($project->billing_type == 1) {
                  echo '<p class="bold">' . _l('project_total_cost') . '</p>';
                  echo '<p>' . app_format_money($project->project_cost, $currency) . '</p>';
              } else {
                  echo '<p class="bold">' . _l('project_rate_per_hour') . '</p>';
                  echo '<p>' . app_format_money($project->project_rate_per_hour, $currency) . '</p>';
              }
              echo '</div>';
          }
   ?>
        <div class="tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project_status'); ?></p>
            <p><?php echo $project_status['name']; ?></p>
        </div>
        <div class="tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project_start_date'); ?></p>
            <p><?php echo _d($project->start_date); ?></p>
        </div>
        <?php if ($project->deadline) { ?>
        <div class="tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project_deadline'); ?></p>
            <p><?php echo _d($project->deadline); ?></p>
        </div>
        <?php } ?>
        <?php if ($project->date_finished) { ?>
        <div class="text-success tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project_completed_date'); ?></p>
            <p><?php echo _d($project->date_finished); ?></p>
        </div>
        <?php } ?>
        <?php if ($project->billing_type == 1 && $project->settings->view_task_total_logged_time == 1) { ?>
        <div class="project-total-logged-hours tw-flex tw-space-x-4">
            <p class="bold"><?php echo _l('project_overview_total_logged_hours'); ?></p>
            <p><?php echo seconds_to_time_format($this->projects_model->total_logged_time($project->id)); ?>
            </p>
        </div>
        <?php } ?>
        <?php $custom_fields = get_custom_fields('projects', ['show_on_client_portal' => 1]);
  if (count($custom_fields) > 0) { ?>
        <?php foreach ($custom_fields as $field) { ?>
        <?php $value = get_custom_field_value($project->id, $field['id'], 'projects');
      if ($value == '') {
          continue;
      } ?>
        <div class="tw-flex tw-space-x-4">
            <p class="bold"><?php echo ucfirst($field['name']); ?></p>
            <p><?php echo $value; ?></p>
        </div>
        <?php } ?>
        <?php } ?>

    </div>
    <div class="col-md-6">
        <div
            class="tw-rounded-md tw-border tw-border-solid tw-border-neutral-100 tw-bg-neutral-50 tw-py-2 tw-px-3 tw-mb-3">
            <div class="row">
                <div class="col-md-9">
                    <p class="project-info tw-mb-2 tw-font-medium tw-tracking-tight">
                        <?php echo _l('project_progress_text'); ?> <span
                            class="tw-text-neutral-500"><?php echo $progress; ?>%</span>
                    </p>
                </div>
                <div class="col-md-3 text-right">
                    <i class="fa-solid fa-bars-progress text-muted" aria-hidden="true"></i>
                </div>
            </div>
            <div class="progress tw-my-0 progress-bar-mini">
                <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar"
                    aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                    data-percent="<?php echo $progress; ?>">
                </div>
            </div>
        </div>
        <?php if ($project->settings->view_tasks == 1) { ?>
        <div class="project-progress-bars tw-mb-3">
            <div class="tw-rounded-md tw-border tw-border-solid tw-border-neutral-100 tw-bg-neutral-50 tw-py-2 tw-px-3">
                <div class="row">
                    <div class="col-md-9">
                        <p class="bold text-dark font-medium tw-mb-0">
                            <span dir="ltr"><?php echo $tasks_not_completed; ?> / <?php echo $total_tasks; ?></span>
                            <?php echo _l('project_open_tasks'); ?>
                        </p>
                        <p class="tw-text-neutral-600 tw-font-medium"><?php echo $tasks_not_completed_progress; ?>%</p>
                    </div>
                    <div class="col-md-3 text-right">
                        <i class="fa-regular fa-check-circle<?php echo $tasks_not_completed_progress >= 100 ? ' text-success' : ' text-muted'; ?>"
                            aria-hidden="true"></i>
                    </div>
                    <div class="col-md-12">
                        <div class="progress tw-my-0 progress-bar-mini">
                            <div class="progress-bar progress-bar-success no-percent-text not-dynamic"
                                role="progressbar" aria-valuenow="<?php echo $tasks_not_completed_progress; ?>"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                data-percent="<?php echo $tasks_not_completed_progress; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($project->deadline) { ?>
        <div class="project-progress-bars">
            <div class="tw-rounded-md tw-border tw-border-solid tw-border-neutral-100 tw-bg-neutral-50 tw-py-2 tw-px-3">
                <div class="row">
                    <div class="col-md-9">
                        <p class="bold text-dark font-medium tw-mb-0">
                            <span dir="ltr"><?php echo $project_days_left; ?> /
                                <?php echo $project_total_days; ?></span>
                            <?php echo _l('project_days_left'); ?>
                        </p>
                        <p class="tw-text-neutral-600 tw-font-medium"><?php echo $project_time_left_percent; ?>%</p>
                    </div>
                    <div class="col-md-3 text-right">
                        <i class="fa-regular fa-calendar-check<?php echo $project_time_left_percent >= 100 ? ' text-success' : ' text-muted'; ?>"
                            aria-hidden="true"></i>
                    </div>
                    <div class="col-md-12">
                        <div class="progress tw-my-0 progress-bar-mini">
                            <div class="progress-bar<?php echo $project_time_left_percent == 0 ? ' progress-bar-warning ' : ' progress-bar-success '; ?>no-percent-text not-dynamic"
                                role="progressbar" aria-valuenow="<?php echo $project_time_left_percent; ?>"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                data-percent="<?php echo $project_time_left_percent; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>

    <div class="clearfix"></div>

    <?php if ($project->settings->view_finance_overview == 1) { ?>
    <div class="col-md-12 project-overview-column">
        <div class="row">
            <div class="col-md-12">
                <hr />
                <?php
       if ($project->billing_type == 3 || $project->billing_type == 2) { ?>
                <div class="row">
                    <div class="col-md-3">
                        <?php
                          $data = $this->projects_model->total_logged_time_by_billing_type($project->id);
                        ?>
                        <p class="tw-mb-0 text-muted">
                            <?php echo _l('project_overview_logged_hours'); ?>
                            <span class="bold"><?php echo $data['logged_time']; ?></span>
                        </p>
                        <p class="tw-font-medium tw-text-neutral-600 tw-mb-0">
                            <?php echo app_format_money($data['total_money'], $currency); ?>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <?php
                          $data = $this->projects_model->data_billable_time($project->id);
                        ?>
                        <p class="text-info tw-mb-0">
                            <?php echo _l('project_overview_billable_hours'); ?>
                            <span class="bold"><?php echo $data['logged_time'] ?></span>
                        </p>
                        <p class="tw-font-medium tw-text-neutral-600 tw-mb-0">
                            <?php echo app_format_money($data['total_money'], $currency); ?>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <?php
                          $data = $this->projects_model->data_billed_time($project->id);
                        ?>
                        <p class="text-success tw-mb-0">
                            <?php echo _l('project_overview_billed_hours'); ?>
                            <span class="bold"><?php echo $data['logged_time']; ?></span>
                        </p>
                        <p class="tw-font-medium tw-text-neutral-600 tw-mb-0">
                            <?php echo app_format_money($data['total_money'], $currency); ?>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <?php
                          $data = $this->projects_model->data_unbilled_time($project->id);
                        ?>
                        <p class="text-danger tw-mb-0">
                            <?php echo _l('project_overview_unbilled_hours'); ?>
                            <span class="bold"><?php echo $data['logged_time']; ?></span>
                        </p>
                        <p class="tw-font-medium tw-text-neutral-600 tw-mb-0">
                            <?php echo app_format_money($data['total_money'], $currency); ?>
                        </p>
                    </div>
                </div>
                <hr />
                <?php } ?>
            </div>
        </div>
        <?php if ($project->settings->available_features['project_expenses'] == 1) { ?>
        <div class="row">
            <div class="col-md-3">
                <p class="text-muted tw-mb-0"><?php echo _l('project_overview_expenses'); ?></span></p>
                <p class="tw-font-medium tw-font-neutral-500 tw-mb-0">
                    <?php echo app_format_money(sum_from_table(db_prefix() . 'expenses', ['where' => ['project_id' => $project->id], 'field' => 'amount']), $currency); ?>
                </p>
            </div>
            <div class="col-md-3">
                <p class="text-info tw-mb-0">
                    <?php echo _l('project_overview_expenses_billable'); ?></span></p>
                <p class="tw-font-medium tw-font-neutral-500 tw-mb-0">
                    <?php echo app_format_money(sum_from_table(db_prefix() . 'expenses', ['where' => ['project_id' => $project->id, 'billable' => 1], 'field' => 'amount']), $currency); ?>
                </p>
            </div>
            <div class="col-md-3">
                <p class="text-success tw-mb-0">
                    <?php echo _l('project_overview_expenses_billed'); ?></span></p>
                <p class="tw-font-medium tw-font-neutral-500 tw-mb-0">
                    <?php echo app_format_money(sum_from_table(db_prefix() . 'expenses', ['where' => ['project_id' => $project->id, 'invoiceid !=' => 'NULL', 'billable' => 1], 'field' => 'amount']), $currency); ?>
                </p>
            </div>
            <div class="col-md-3">
                <p class="text-danger tw-mb-0">
                    <?php echo _l('project_overview_expenses_unbilled'); ?></span></p>
                <p class="tw-font-medium tw-font-neutral-500 tw-mb-0">
                    <?php echo app_format_money(sum_from_table(db_prefix() . 'expenses', ['where' => ['project_id' => $project->id, 'invoiceid IS NULL', 'billable' => 1], 'field' => 'amount']), $currency); ?>
                </p>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
    <div class="col-md-12">
        <hr />
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <h4 class="tw-font-semibold tw-text-base tw-mt-0 tw-mb-4"><?php echo _l('project_description'); ?></h4>
        <div class="tc-content project-description tw-text-neutral-600">
            <?php if (empty($project->description)) { ?>
            <p class="text-center tw-mb-0">
                <?php echo _l('no_description_project'); ?>
            </p>
            <?php }
                echo check_for_links($project->description);
            ?>
        </div>
    </div>
</div>