<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if ($proposal['status'] == $status) { ?>
<li data-proposal-id="<?php echo $proposal['id']; ?>" class="<?php if ($proposal['invoice_id'] != null || $proposal['estimate_id'] != null) {
    echo 'not-sortable';
} ?>">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-font-semibold  tw-text-base pipeline-heading tw-mb-0.5">
                    <a href="<?php echo admin_url('proposals/list_proposals/' . $proposal['id']); ?>"
                        data-toggle="tooltip" data-title="<?php echo $proposal['subject']; ?>"
                        class="tw-text-neutral-700 hover:tw-text-neutral-900 active:tw-text-neutral-900"
                        onclick="proposal_pipeline_open(<?php echo $proposal['id']; ?>); return false;">
                        <?php echo format_proposal_number($proposal['id']); ?>
                    </a>
                    <?php if (has_permission('estimates', '', 'edit')) { ?>
                    <a href="<?php echo admin_url('proposals/proposal/' . $proposal['id']); ?>" target="_blank"
                        class="pull-right">
                        <small>
                            <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                        </small>
                    </a>
                    <?php } ?>
                </h4>
                <span class="tw-inline-block tw-w-full tw-mb-2">
                    <?php
               if ($proposal['rel_type'] == 'lead') {
                   echo '<a href="' . admin_url('leads/index/' . $proposal['rel_id']) . '" onclick="init_lead(' . $proposal['rel_id'] . '); return false;" data-toggle="tooltip" data-title="' . _l('lead') . '">' . $proposal['proposal_to'] . '</a><br />';
               } elseif ($proposal['rel_type'] == 'customer') {
                   echo '<a href="' . admin_url('clients/client/' . $proposal['rel_id']) . '" data-toggle="tooltip" data-title="' . _l('client') . '">' . $proposal['proposal_to'] . '</a><br />';
               }
               ?>
                </span>
            </div>
            <div class="col-md-12">

                <div class="tw-flex">
                    <div class="tw-grow">
                        <?php if ($proposal['total'] != 0) { ?>
                        <p class="tw-mb-0 tw-text-sm tw-text-neutral-700">
                            <span class="tw-text-neutral-500">
                                <?php echo _l('proposal_total'); ?>:
                            </span>
                            <?php echo app_format_money($proposal['total'], get_currency($proposal['currency'])); ?>
                        </p>
                        <?php } ?>
                        <p class="tw-mb-0 tw-text-sm tw-text-neutral-700">
                            <span class="tw-text-neutral-500">
                                <?php echo _l('proposal_date'); ?>:
                            </span>
                            <?php echo _d($proposal['date']); ?>
                        </p>
                        <?php if (is_date($proposal['open_till'])) { ?>
                        <p class="tw-mb-0 tw-text-sm tw-text-neutral-700">
                            <span class="tw-text-neutral-500">
                                <?php echo _l('proposal_open_till'); ?>:
                            </span>
                            <?php echo _d($proposal['open_till']); ?>
                        </p>
                        <?php } ?>
                    </div>
                    <div class="tw-shrink-0 text-right">
                        <small>
                            <i class="fa-regular fa-comments" aria-hidden="true"></i>
                            <?php echo _l('proposal_comments'); ?>: <?php echo total_rows(db_prefix() . 'proposal_comments', [
                     'proposalid' => $proposal['id'],
                     ]); ?>
                        </small>
                    </div>
                </div>
                <?php $tags = get_tags_in($proposal['id'], 'proposal'); ?>
                <?php if (count($tags) > 0) { ?>
                <div class="kanban-tags tw-text-sm tw-inline-flex">
                    <?php echo render_tags($tags); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</li>
<?php } ?>