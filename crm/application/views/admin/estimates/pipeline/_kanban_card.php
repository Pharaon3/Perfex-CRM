<?php defined('BASEPATH') or exit('No direct script access allowed');
   if ($estimate['status'] == $status) { ?>
<li data-estimate-id="<?php echo $estimate['id']; ?>" class="<?php if ($estimate['invoiceid'] != null) {
       echo 'not-sortable';
   } ?>">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-semibold tw-text-base pipeline-heading tw-mb-0.5">
                    <a href="<?php echo admin_url('estimates/list_estimates/' . $estimate['id']); ?>"
                        class="tw-text-neutral-700 hover:tw-text-neutral-900 active:tw-text-neutral-900"
                        onclick="estimate_pipeline_open(<?php echo $estimate['id']; ?>); return false;">
                        <?php echo format_estimate_number($estimate['id']); ?>
                    </a>
                    <?php if (has_permission('estimates', '', 'edit')) { ?>
                    <a href="<?php echo admin_url('estimates/estimate/' . $estimate['id']); ?>" target="_blank"
                        class="pull-right">
                        <small>
                            <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                        </small>
                    </a>
                    <?php } ?>
                </h4>
                <span class="tw-inline-block tw-w-full tw-mb-2">
                    <a href="<?php echo admin_url('clients/client/' . $estimate['clientid']); ?>" target="_blank">
                        <?php echo $estimate['company']; ?>
                    </a>
                </span>
            </div>
            <div class="col-md-12">
                <div class="tw-flex">
                    <div class="tw-grow">
                        <p class="tw-mb-0 tw-text-sm tw-text-neutral-700">
                            <span class="tw-text-neutral-500">
                                <?php echo _l('estimate_total'); ?>:
                            </span>
                            <?php echo app_format_money($estimate['total'], $estimate['currency_name']); ?>
                        </p>
                        <p class="tw-mb-0 tw-text-sm tw-text-neutral-700">
                            <span class="tw-text-neutral-500">
                                <?php echo _l('estimate_data_date'); ?>:
                            </span>
                            <?php echo _d($estimate['date']); ?>
                        </p>
                        <?php if (is_date($estimate['expirydate']) || !empty($estimate['expirydate'])) { ?>
                        <p class="tw-mb-0 tw-text-sm tw-text-neutral-700">
                            <span class="tw-text-neutral-500">
                                <?php echo _l('estimate_data_expiry_date'); ?>:
                            </span>
                            <?php echo _d($estimate['expirydate']); ?>
                        </p>
                        <?php } ?>
                    </div>
                    <div class="tw-shrink-0 text-right">
                        <small>
                            <i class="fa fa-paperclip"></i>
                            <?php echo _l('estimate_notes'); ?>:
                            <?php echo total_rows(db_prefix() . 'notes', [
                        'rel_id'   => $estimate['id'],
                        'rel_type' => 'estimate',
                    ]); ?>
                        </small>
                    </div>
                    <?php $tags = get_tags_in($estimate['id'], 'estimate'); ?>
                    <?php if (count($tags) > 0) { ?>
                    <div class="kanban-tags tw-text-sm tw-inline-flex">
                        <?php echo render_tags($tags); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</li>
<?php } ?>