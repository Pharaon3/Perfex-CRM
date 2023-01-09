<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
    <div class="widget-dragger"></div>
    <div class="row">
        <?php
         $initial_column = 'col-lg-3';
         if (!is_staff_member() && ((!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own') && (get_option('allow_staff_view_invoices_assigned') == 0
           || (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices()))))) {
             $initial_column = 'col-lg-6';
         } elseif (!is_staff_member() || (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own') && (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices()) || (get_option('allow_staff_view_invoices_assigned') == 0 && (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own'))))) {
             $initial_column = 'col-lg-4';
         }
      ?>
        <?php if (has_permission('invoices', '', 'view') || has_permission('invoices', '', 'view_own') || (get_option('allow_staff_view_invoices_assigned') == '1' && staff_has_assigned_invoices())) { ?>
        <div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
                <?php
                  $total_invoices                          = total_rows(db_prefix() . 'invoices', 'status NOT IN (5,6)' . (!has_permission('invoices', '', 'view') ? ' AND ' . get_invoices_where_sql_for_staff(get_staff_user_id()) : ''));
                  $total_invoices_awaiting_payment         = total_rows(db_prefix() . 'invoices', 'status NOT IN (2,5,6)' . (!has_permission('invoices', '', 'view') ? ' AND ' . get_invoices_where_sql_for_staff(get_staff_user_id()) : ''));
                  $percent_total_invoices_awaiting_payment = $total_invoices > 0 ? (($total_invoices_awaiting_payment * 100) / $total_invoices) : 0;
                  $percent_total_invoices_awaiting_payment = number_format($percent_total_invoices_awaiting_payment > 0 && $percent_total_invoices_awaiting_payment < 1 ? ceil($percent_total_invoices_awaiting_payment) : $percent_total_invoices_awaiting_payment, 2)
                  ?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-mr-3 rtl:tw-ml-3 tw-text-neutral-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                        <?php echo _l('invoices_awaiting_payment'); ?>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
                        <?php echo $total_invoices_awaiting_payment; ?> /
                        <?php echo $total_invoices; ?>
                    </span>
                </div>

                <div class="progress tw-mb-0 tw-mt-4 progress-bar-mini">
                    <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar"
                        aria-valuenow="<?php echo $percent_total_invoices_awaiting_payment; ?>" aria-valuemin="0"
                        aria-valuemax="100" style="width: 0%"
                        data-percent="<?php echo $percent_total_invoices_awaiting_payment; ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if (is_staff_member()) { ?>
        <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
                <?php
                  $where = '';
                  if (!is_admin()) {
                      $where .= '(addedfrom = ' . get_staff_user_id() . ' OR assigned = ' . get_staff_user_id() . ')';
                  }
                  // Junk leads are excluded from total
                  $total_leads = total_rows(db_prefix() . 'leads', ($where == '' ? 'junk=0' : $where .= ' AND junk =0'));
                  if ($where == '') {
                      $where .= 'status=1';
                  } else {
                      $where .= ' AND status =1';
                  }
                  $total_leads_converted         = total_rows(db_prefix() . 'leads', $where);
                  $percent_total_leads_converted = ($total_leads > 0 ? number_format(($total_leads_converted * 100) / $total_leads, 2) : 0);
                  ?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-mr-3 rtl:tw-ml-3 tw-text-neutral-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        <?php echo _l('leads_converted_to_client'); ?>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
                        <?php echo $total_leads_converted; ?> /
                        <?php echo $total_leads; ?>
                    </span>
                </div>

                <div class="progress tw-mb-0 tw-mt-4 progress-bar-mini">
                    <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar"
                        aria-valuenow="<?php echo $percent_total_leads_converted; ?>" aria-valuemin="0"
                        aria-valuemax="100" style="width: 0%"
                        data-percent="<?php echo $percent_total_leads_converted; ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="quick-stats-projects col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
                <?php
                  $_where         = '';
                  $project_status = get_project_status_by_id(2);
                  if (!has_permission('projects', '', 'view')) {
                      $_where = 'id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')';
                  }
                  $total_projects               = total_rows(db_prefix() . 'projects', $_where);
                  $where                        = ($_where == '' ? '' : $_where . ' AND ') . 'status = 2';
                  $total_projects_in_progress   = total_rows(db_prefix() . 'projects', $where);
                  $percent_in_progress_projects = ($total_projects > 0 ? number_format(($total_projects_in_progress * 100) / $total_projects, 2) : 0);
                  ?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex tw-items-center text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-mr-3 rtl:tw-ml-3 tw-text-neutral-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                        <?php echo _l('projects') . ' ' . $project_status['name']; ?>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
                        <?php echo $total_projects_in_progress; ?> /
                        <?php echo $total_projects; ?>
                    </span>
                </div>

                <div class="progress tw-mb-0 tw-mt-5 progress-bar-mini">
                    <div class="progress-bar no-percent-text not-dynamic"
                        style="background:<?php echo $project_status['color']; ?>" role="progressbar"
                        aria-valuenow="<?php echo $percent_in_progress_projects; ?>" aria-valuemin="0"
                        aria-valuemax="100" style="width: 0%"
                        data-percent="<?php echo $percent_in_progress_projects; ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper">
                <?php
                  $_where = '';
                  if (!has_permission('tasks', '', 'view')) {
                      $_where = db_prefix() . 'tasks.id IN (SELECT taskid FROM ' . db_prefix() . 'task_assigned WHERE staffid = ' . get_staff_user_id() . ')';
                  }
                  $total_tasks                = total_rows(db_prefix() . 'tasks', $_where);
                  $where                      = ($_where == '' ? '' : $_where . ' AND ') . 'status != ' . Tasks_model::STATUS_COMPLETE;
                  $total_not_finished_tasks   = total_rows(db_prefix() . 'tasks', $where);
                  $percent_not_finished_tasks = ($total_tasks > 0 ? number_format(($total_not_finished_tasks * 100) / $total_tasks, 2) : 0);
                  ?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-mr-3 rtl:tw-ml-3 tw-text-neutral-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />
                        </svg>
                        <?php echo _l('tasks_not_finished'); ?>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
                        <?php echo $total_not_finished_tasks; ?> / <?php echo $total_tasks; ?>
                    </span>
                </div>
                <div class="progress tw-mb-0 tw-mt-4 progress-bar-mini">
                    <div class="progress-bar progress-bar-default no-percent-text not-dynamic" role="progressbar"
                        aria-valuenow="<?php echo $percent_not_finished_tasks; ?>" aria-valuemin="0" aria-valuemax="100"
                        style="width: 0%" data-percent="<?php echo $percent_not_finished_tasks; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>