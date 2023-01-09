<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="#" onclick="new_status(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('estimate_request_new_status'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php if (count($statuses) > 0) { ?>
                        <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th><?php echo _l('id'); ?></th>
                                <th><?php echo _l('estimate_request_status_table_name'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ($statuses as $status) { ?>
                                <tr>
                                    <td>
                                        <?php echo $status['id']; ?>
                                    </td>
                                    <td><a href="#"
                                            onclick="edit_status(this,<?php echo $status['id']; ?>);return false;"
                                            data-color="<?php echo $status['color']; ?>"
                                            data-name="<?php echo $status['name']; ?>"
                                            data-order="<?php echo $status['statusorder']; ?>"><?php echo $status['name']; ?></a><br />
                                        <span class="text-muted">
                                            <?php echo _l('estimate_request_table_total', total_rows(db_prefix() . 'estimate_requests', ['status' => $status['id']])); ?></span>
                                    </td>
                                    <td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <a href="#"
                                                onclick="edit_status(this,<?php echo $status['id']; ?>);return false;"
                                                data-color="<?php echo $status['color']; ?>"
                                                data-name="<?php echo $status['name']; ?>"
                                                data-order="<?php echo $status['statusorder']; ?>"
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <?php
                                                if (empty($status['flag'])) { ?>
                                            <a href="<?php echo admin_url('estimate_request/delete_status/' . $status['id']); ?>"
                                                class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } else { ?>
                        <p class="no-margin"><?php echo _l('estimate_request_statuses_not_found'); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once(APPPATH . 'views/admin/estimate_request/status.php'); ?>
<?php init_tail(); ?>
</body>

</html>