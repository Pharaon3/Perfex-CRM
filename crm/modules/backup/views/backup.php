<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-self-end">
                        <?php echo $title; ?>
                    </h4>
                    <div>
                        <a href="#" data-toggle="modal" data-target="#auto_backup_config"
                            class="btn btn-default mright5">
                            <?php echo _l('auto_backup'); ?>
                        </a>
                        <a href="<?php echo admin_url('backup/make_backup_db'); ?>" class="btn btn-primary">
                            <?php echo _l('utility_create_new_backup_db'); ?>
                        </a>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <div class="alert alert-info mbot25">
                            <?php echo _l('utility_db_backup_note'); ?>
                        </div>
                        <table class="table dt-table" data-order-col="2" data-order-type="desc">
                            <thead>
                                <th><?php echo _l('utility_backup_table_backupname'); ?></th>
                                <th><?php echo _l('utility_backup_table_backupsize'); ?></th>
                                <th><?php echo _l('utility_backup_table_backupdate'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php $backups = list_files(BACKUPS_FOLDER); ?>
                                <?php foreach ($backups as $backup) {
    $fullPath              = BACKUPS_FOLDER . $backup;
    $backupNameNoExtension = preg_replace('/\\.[^.\\s]{3,4}$/', '', $backup); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo site_url('backup/download/' . $backupNameNoExtension); ?>">
                                            <?php echo $backup; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo bytesToSize($fullPath); ?>
                                    </td>
                                    <td data-order="<?php echo date('Y-m-d H:m:s', filectime($fullPath)); ?>">
                                        <?php echo date('M dS, Y, g:i a', filectime($fullPath)); ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo admin_url('backup/delete/' . $backupNameNoExtension); ?>"
                                            class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                            <i class="fa-regular fa-trash-can fa-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="auto_backup_config" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('backup/update_auto_backup_options')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('auto_backup'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo render_yes_no_option('auto_backup_enabled', 'auto_backup_enabled'); ?>
                <div data-toggle="tooltip" title="<?php echo _l('hour_of_day_perform_auto_operations_format'); ?>">
                    <?php echo render_input('auto_backup_hour', 'auto_backup_hour', get_option('auto_backup_hour'), 'number'); ?>
                </div>
                <?php echo render_input('auto_backup_every', 'auto_backup_every', get_option('auto_backup_every'), 'number'); ?>
                <?php echo render_input('delete_backups_older_then', 'delete_backups_older_then', get_option('delete_backups_older_then'), 'number'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
</body>

</html>