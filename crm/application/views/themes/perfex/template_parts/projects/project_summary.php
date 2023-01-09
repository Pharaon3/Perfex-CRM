<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-2 sm:tw-gap-4 tw-mb-10">
    <?php foreach ($project_statuses as $status) { ?>
    <a href="<?php echo site_url('clients/projects/' . $status['id']); ?>"
        class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md hover:tw-bg-neutral-100 <?php echo isset($list_statuses) && in_array($status['id'], $list_statuses) ? 'tw-bg-white' : 'tw-bg-neutral-50 '; ?>">
        <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
            <dt class="tw-font-medium" style="color:<?php echo $status['color']; ?>">
                <?php echo $status['name']; ?>
            </dt>
            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                    <?php echo total_rows(db_prefix() . 'projects', ['status' => $status['id'], 'clientid' => get_client_user_id()]); ?>
                </div>
            </dd>
        </div>
    </a>
    <?php } ?>
</dl>