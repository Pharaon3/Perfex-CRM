<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-6 project-overview-left">
        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <p class="project-info tw-mb-0 tw-font-medium tw-text-base tw-tracking-tight">
                            <?php echo _l('project_progress_text'); ?> <span
                                class="tw-text-neutral-500"><?php echo $percent; ?>%</span>
                        </p>
                        <div class="progress progress-bar-mini">
                            <div class="progress-bar progress-bar-success no-percent-text not-dynamic"
                                role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0"
                                aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent; ?>">
                            </div>
                        </div>
                        <?php hooks()->do_action('admin_area_after_project_progress') ?>
                        <hr class="hr-panel-separator" />
                    </div>

                    <?php if (count($project->shared_vault_entries) > 0) { ?>
                    <?php $this->load->view('admin/clients/vault_confirm_password'); ?>
                    <div class="col-md-12">
                        <p class="tw-font-medium">
                            <a href="#" onclick="slideToggle('#project_vault_entries'); return false;"
                                class="tw-inline-flex tw-items-center tw-space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                </svg>
                                <span>
                                    <?php echo _l('project_shared_vault_entry_login_details'); ?>
                                </span>
                            </a>
                        </p>
                        <div id="project_vault_entries"
                            class="hide tw-mb-4 tw-bg-neutral-50 tw-px-4 tw-py-2 tw-rounded-md">
                            <?php foreach ($project->shared_vault_entries as $vault_entry) { ?>
                            <div class="tw-my-3">
                                <div class="row" id="<?php echo 'vaultEntry-' . $vault_entry['id']; ?>">
                                    <div class="col-md-6">
                                        <p class="mtop5">
                                            <b><?php echo _l('server_address'); ?>:
                                            </b><?php echo $vault_entry['server_address']; ?>
                                        </p>
                                        <p class="tw-mb-0">
                                            <b><?php echo _l('port'); ?>:
                                            </b><?php echo !empty($vault_entry['port']) ? $vault_entry['port'] : _l('no_port_provided'); ?>
                                        </p>
                                        <p class="tw-mb-0">
                                            <b><?php echo _l('vault_username'); ?>:
                                            </b><?php echo $vault_entry['username']; ?>
                                        </p>
                                        <p class="no-margin">
                                            <b><?php echo _l('vault_password'); ?>: </b><span
                                                class="vault-password-fake">
                                                <?php echo str_repeat('&bull;', 10); ?> </span><span
                                                class="vault-password-encrypted"></span> <a href="#"
                                                class="vault-view-password mleft10" data-toggle="tooltip"
                                                data-title="<?php echo _l('view_password'); ?>"
                                                onclick="vault_re_enter_password(<?php echo $vault_entry['id']; ?>,this); return false;"><i
                                                    class="fa fa-lock" aria-hidden="true"></i></a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (!empty($vault_entry['description'])) { ?>
                                        <p class="tw-mb-0">
                                            <b><?php echo _l('vault_description'); ?>:
                                            </b><br /><?php echo $vault_entry['description']; ?>
                                        </p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="col-md-12">
                        <h4 class="tw-font-semibold tw-text-base tw-mb-4">
                            <?php echo _l('project_overview'); ?>
                        </h4>
                        <dl class="tw-grid tw-grid-cols-1 tw-gap-x-4 tw-gap-y-5 sm:tw-grid-cols-2">
                            <div class="sm:tw-col-span-1 project-overview-id">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project'); ?> <?php echo _l('the_number_sign'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900"><?php echo $project->id; ?></dd>
                            </div>

                            <div class="sm:tw-col-span-1 project-overview-customer">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_customer'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <a
                                        href="<?php echo admin_url(); ?>clients/client/<?php echo $project->clientid; ?>">
                                        <?php echo $project->client_data->company; ?>
                                    </a>
                                </dd>
                            </div>

                            <?php if (has_permission('projects', '', 'edit')) { ?>
                            <div class="sm:tw-col-span-1 project-overview-billing">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_billing_type'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
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
                                </dd>
                            </div>
                            <?php if ($project->billing_type == 1 || $project->billing_type == 2) { ?>
                            <div class="sm:tw-col-span-1 project-overview-amount">
                                <?php if ($project->billing_type == 1) { ?>
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_total_cost'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo  app_format_money($project->project_cost, $currency); ?>
                                </dd>
                                <?php  } else { ?>
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_rate_per_hour'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo app_format_money($project->project_rate_per_hour, $currency); ?>
                                </dd>
                                <?php } ?>
                            </div>
                            <?php } } ?>

                            <div class="sm:tw-col-span-1 project-overview-status">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_status'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo $project_status['name']; ?>
                                </dd>
                            </div>

                            <div class="sm:tw-col-span-1 project-overview-date-created">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_datecreated'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo _d($project->project_created); ?>
                                </dd>
                            </div>
                            <div class="sm:tw-col-span-1 project-overview-start-date">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_start_date'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo _d($project->start_date); ?>
                                </dd>
                            </div>
                            <?php if ($project->deadline) { ?>
                            <div class="sm:tw-col-span-1 project-overview-deadline">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_deadline'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo _d($project->deadline); ?>
                                </dd>
                            </div>
                            <?php } ?>

                            <?php if ($project->date_finished) { ?>
                            <div class="sm:tw-col-span-1 project-overview-date-finished">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_completed_date'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm text-success">
                                    <?php echo _dt($project->date_finished); ?>
                                </dd>
                            </div>
                            <?php } ?>

                            <?php if ($project->estimated_hours && $project->estimated_hours != '0') { ?>
                            <div class="sm:tw-col-span-1 project-overview-estimated-hours">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('estimated_hours'); ?>
                                </dt>
                                <dd
                                    class="tw-mt-1 tw-text-sm <?php echo hours_to_seconds_format($project->estimated_hours) < (int)$project_total_logged_time ? 'text-warning' : 'text-neutral-900'; ?>">
                                    <?php echo str_replace('.', ':', $project->estimated_hours); ?>
                                </dd>
                            </div>
                            <?php } ?>

                            <div class="sm:tw-col-span-1 project-overview-total-logged-hours">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_overview_total_logged_hours'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo seconds_to_time_format($project_total_logged_time); ?>
                                </dd>
                            </div>


                            <?php $custom_fields = get_custom_fields('projects');
         if (count($custom_fields) > 0) { ?>
                            <?php foreach ($custom_fields as $field) { ?>
                            <?php $value = get_custom_field_value($project->id, $field['id'], 'projects');
         if ($value == '') {
             continue;
         } ?>
                            <div class="sm:tw-col-span-1">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo ucfirst($field['name']); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <?php echo $value; ?>
                                </dd>
                            </div>
                            <?php } ?>
                            <?php } ?>

                            <?php $tags = get_tags_in($project->id, 'project'); ?>
                            <?php if (count($tags) > 0) { ?>
                            <div class="sm:tw-col-span-1 project-overview-tags">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('tags'); ?>
                                </dt>
                                <dd class="tags-read-only-custom tw-mt-1 tw-text-sm tw-text-neutral-900">
                                    <input type="text" class="tagsinput read-only" id="tags" name="tags"
                                        value="<?php echo prep_tags_input($tags); ?>" data-role="tagsinput">
                                </dd>
                            </div>
                            <?php } ?>
                            <div class="clearfix"></div>
                            <div class="sm:tw-col-span-2 project-overview-description tc-content">
                                <dt class="tw-text-sm tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('project_description'); ?>
                                </dt>
                                <dd class="tw-mt-1 tw-space-y-5 tw-text-sm tw-text-neutral-900">
                                    <?php if (empty($project->description)) { ?>
                                    <p class="text-muted tw-mb-0">
                                        <?php echo _l('no_description_project'); ?>
                                    </p>
                                    <?php } ?>
                                    <?php echo check_for_links($project->description); ?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 project-overview-right">
        <div class="row">
            <div class="col-md-<?php echo($project->deadline ? 6 : 12); ?> project-progress-bars">
                <div class="project-overview-open-tasks">
                    <div class="panel_s">
                        <div class="panel-body !tw-px-5 !tw-py-4">
                            <div class="row">
                                <div class="col-md-9">
                                    <p class="bold text-dark tw-mb-2">
                                        <span dir="ltr"><?php echo $tasks_not_completed; ?> /
                                            <?php echo $total_tasks; ?></span>
                                        <?php echo _l('project_open_tasks'); ?>
                                    </p>
                                    <p class="text-muted bold tw-mb-0"><?php echo $tasks_not_completed_progress; ?>%</p>
                                </div>
                                <div class="col-md-3 text-right">
                                    <i class="fa-regular fa-check-circle<?php echo $tasks_not_completed_progress >= 100 ? ' text-success' : ' text-muted'; ?>"
                                        aria-hidden="true"></i>
                                </div>
                                <div class="col-md-12 mtop5">
                                    <div class="progress no-margin progress-bar-mini">
                                        <div class="progress-bar progress-bar-success no-percent-text not-dynamic"
                                            role="progressbar"
                                            aria-valuenow="<?php echo $tasks_not_completed_progress; ?>"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                            data-percent="<?php echo $tasks_not_completed_progress; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($project->deadline) { ?>
            <div class="col-md-6 project-progress-bars project-overview-days-left">
                <div class="panel_s">
                    <div class="panel-body !tw-px-5 !tw-py-4">
                        <div class="row">
                            <div class="col-md-9">
                                <p class="bold text-dark tw-mb-2">
                                    <span dir="ltr"><?php echo $project_days_left; ?> /
                                        <?php echo $project_total_days; ?></span>
                                    <?php echo _l('project_days_left'); ?>
                                </p>
                                <p class="text-muted bold tw-mb-0"><?php echo $project_time_left_percent; ?>%</p>
                            </div>
                            <div class="col-md-3 text-right">
                                <i class="fa-regular fa-calendar-check<?php echo $project_time_left_percent >= 100 ? ' text-success' : ' text-muted'; ?>"
                                    aria-hidden="true"></i>
                            </div>
                            <div class="col-md-12 mtop5">
                                <div class="progress no-margin progress-bar-mini">
                                    <div class="progress-bar<?php if ($project_time_left_percent == 0) {
             echo ' progress-bar-warning ';
         } else {
             echo ' progress-bar-success ';
         } ?>no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $project_time_left_percent; ?>"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                                        data-percent="<?php echo $project_time_left_percent; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <?php if (has_permission('projects', '', 'create')) { ?>
        <div class="row">
            <?php if ($project->billing_type == 3 || $project->billing_type == 2) { ?>
            <div class="col-md-12 project-overview-logged-hours-finance">
                <div class="panel_s !tw-mb-3">
                    <div class="panel-body !tw-px-5 !tw-py-4">
                        <div class="row">
                            <div class="col-md-3">
                                <?php
         $data = $this->projects_model->total_logged_time_by_billing_type($project->id);
         ?>
                                <p class="tw-mb-0 text-muted"><?php echo _l('project_overview_logged_hours'); ?> <span
                                        class="bold"><?php echo $data['logged_time']; ?></span></p>
                                <p class="bold font-medium tw-mb-0">
                                    <?php echo app_format_money($data['total_money'], $currency); ?></p>
                            </div>
                            <div class="col-md-3">
                                <?php
         $data = $this->projects_model->data_billable_time($project->id);
         ?>
                                <p class="tw-mb-0 text-info"><?php echo _l('project_overview_billable_hours'); ?> <span
                                        class="bold"><?php echo $data['logged_time'] ?></span></p>
                                <p class="bold font-medium tw-mb-0">
                                    <?php echo app_format_money($data['total_money'], $currency); ?></p>
                            </div>
                            <div class="col-md-3">
                                <?php
         $data = $this->projects_model->data_billed_time($project->id);
         ?>
                                <p class="tw-mb-0 text-success"><?php echo _l('project_overview_billed_hours'); ?> <span
                                        class="bold"><?php echo $data['logged_time']; ?></span></p>
                                <p class="bold font-medium tw-mb-0">
                                    <?php echo app_format_money($data['total_money'], $currency); ?></p>
                            </div>
                            <div class="col-md-3">
                                <?php
         $data = $this->projects_model->data_unbilled_time($project->id);
         ?>
                                <p class="tw-mb-0 text-danger"><?php echo _l('project_overview_unbilled_hours'); ?>
                                    <span class="bold"><?php echo $data['logged_time']; ?></span>
                                </p>
                                <p class="bold font-medium tw-mb-0">
                                    <?php echo app_format_money($data['total_money'], $currency); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-md-12 project-overview-expenses-finance">
                <div class="panel_s">
                    <div class="panel-body !tw-px-5 !tw-py-4">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="tw-mb-0 text-muted"><?php echo _l('project_overview_expenses'); ?></p>
                                <p class="bold font-medium tw-mb-0">
                                    <?php echo app_format_money(sum_from_table(db_prefix() . 'expenses', ['where' => ['project_id' => $project->id], 'field' => 'amount']), $currency); ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="tw-mb-0 text-info"><?php echo _l('project_overview_expenses_billable'); ?></p>
                                <p class="bold font-medium tw-mb-0">
                                    <?php echo app_format_money(sum_from_table(db_prefix() . 'expenses', ['where' => ['project_id' => $project->id, 'billable' => 1], 'field' => 'amount']), $currency); ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="tw-mb-0 text-success"><?php echo _l('project_overview_expenses_billed'); ?>
                                </p>
                                <p class="bold font-medium tw-mb-0">
                                    <?php echo app_format_money(sum_from_table(db_prefix() . 'expenses', ['where' => ['project_id' => $project->id, 'invoiceid !=' => 'NULL', 'billable' => 1], 'field' => 'amount']), $currency); ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="tw-mb-0 text-danger"><?php echo _l('project_overview_expenses_unbilled'); ?>
                                </p>
                                <p class="bold font-medium tw-mb-0">
                                    <?php echo app_format_money(sum_from_table(db_prefix() . 'expenses', ['where' => ['project_id' => $project->id, 'invoiceid IS NULL', 'billable' => 1], 'field' => 'amount']), $currency); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="project-overview-timesheets-chart">
            <div class="dropdown pull-right">
                <a href="#" class="dropdown-toggle" type="button" id="dropdownMenuProjectLoggedTime"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?php if (!$this->input->get('overview_chart')) {
             echo _l('this_week');
         } else {
             echo _l($this->input->get('overview_chart'));
         }
         ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuProjectLoggedTime">
                    <li><a
                            href="<?php echo admin_url('projects/view/' . $project->id . '?group=project_overview&overview_chart=this_week'); ?>"><?php echo _l('this_week'); ?></a>
                    </li>
                    <li><a
                            href="<?php echo admin_url('projects/view/' . $project->id . '?group=project_overview&overview_chart=last_week'); ?>"><?php echo _l('last_week'); ?></a>
                    </li>
                    <li><a
                            href="<?php echo admin_url('projects/view/' . $project->id . '?group=project_overview&overview_chart=this_month'); ?>"><?php echo _l('this_month'); ?></a>
                    </li>
                    <li><a
                            href="<?php echo admin_url('projects/view/' . $project->id . '?group=project_overview&overview_chart=last_month'); ?>"><?php echo _l('last_month'); ?></a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
            <div class="panel_s">
                <div class="panel-body !tw-px-5 !tw-py-4">
                    <canvas id="timesheetsChart" style="max-height:300px;" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($project_overview_chart)) { ?>
<script>
var project_overview_chart = <?php echo json_encode($project_overview_chart); ?>;
</script>
<?php } ?>