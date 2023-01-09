<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="stats-top" class="hide">
    <div id="invoices_total"></div>

    <?php
      $where_all           = '';
      $has_permission_view = has_permission('invoices', '', 'view');
      if (isset($project)) {
          $where_all .= 'project_id=' . $project->id . ' AND ';
      }
      if (!$has_permission_view) {
          $where_all .= get_invoices_where_sql_for_staff(get_staff_user_id());
      }
      $where_all = trim($where_all);
      if (endsWith($where_all, ' AND')) {
          $where_all = substr_replace($where_all, '', -4);
      }
      $total_invoices = total_rows(db_prefix() . 'invoices', $where_all);
      ?>
    <div class="quick-top-stats">
        <dl
            class="tw-mt-5 tw-grid tw-grid-cols-1 tw-divide-y tw-divide-solid tw-divide-neutral-200 tw-overflow-hidden md:tw-grid-cols-3 lg:tw-grid-cols-5 md:tw-divide-y-0 md:tw-divide-x tw-mb-0">
            <?php foreach ($invoices_statuses as $status) {
          if ($status == Invoices_model::STATUS_CANCELLED) {
              continue;
          }

          $where = ['status' => $status];
          if (isset($project)) {
              $where['project_id'] = $project->id;
          }
          if (!$has_permission_view) {
              $where['addedfrom'] = get_staff_user_id();
          }
          $total_by_status = total_rows(db_prefix() . 'invoices', $where);
          $percent         = ($total_invoices > 0 ? number_format(($total_by_status * 100) / $total_invoices, 2) : 0); ?>

            <div class="tw-px-3 tw-py-4 sm:tw-p-4">
                <dt class="tw-font-medium text-<?php echo get_invoice_status_label($status); ?>">
                    <?php echo format_invoice_status($status, '', false); ?>
                </dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <?php echo $total_by_status; ?> / <?php echo $total_invoices; ?>
                        <span class="tw-ml-2 tw-text-sm tw-font-medium tw-text-neutral-500">
                            <a href="#" data-cview="invoices_<?php echo $status; ?>"
                                onclick="dt_custom_view('invoices_<?php echo $status; ?>','.table-invoices','invoices_<?php echo $status; ?>',true); return false;">
                                <?php echo _l('view'); ?>
                            </a>
                        </span>
                    </div>
                    <div class="tw-font-medium md:tw-mt-2 lg:tw-mt-0">
                        <?php echo $percent; ?>%
                    </div>
                </dd>
            </div>
            <?php
      } ?>
    </div>
    <hr />
</div>