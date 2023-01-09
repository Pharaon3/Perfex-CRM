<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('projects'); ?></h4>
<?php if (isset($client)) { ?>
<?php if (has_permission('projects', '', 'create')) { ?>
<a href="<?php echo admin_url('projects/project?customer_id=' . $client->userid); ?>"
    class="btn btn-primary mbot15<?php echo $client->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?php echo _l('new_project'); ?>
</a>
<?php } ?>
<?php
      $_where = '';
      if (!has_permission('projects', '', 'view')) {
          $_where = 'id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')';
      }
      ?>
<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-3 sm:tw-gap-5 tw-mb-5">
    <?php foreach ($project_statuses as $status) { ?>
    <div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
        <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
            <dt class="tw-text-base tw-font-normal" style="color:<?php echo $status['color']; ?>">
                <?php echo $status['name']; ?>
            </dt>
            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                <div class="tw-flex tw-items-baseline tw-text-lg tw-font-semibold tw-text-primary-600">
                    <?php $where = ($_where == '' ? '' : $_where . ' AND ') . 'status = ' . $status['id'] . ' AND clientid=' . $client->userid; ?>
                    <?php echo total_rows(db_prefix() . 'projects', $where); ?>
                </div>
            </dd>
        </div>
    </div>
    <?php } ?>
</dl>
<?php
   $this->load->view('admin/projects/table_html', ['class' => 'projects-single-client']);
}
?>