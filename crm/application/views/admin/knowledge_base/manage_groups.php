<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons tw-mb-2 sm:tw-mb-4">
                    <?php if (has_permission('knowledge_base', '', 'create')) { ?>
                    <a href="#" onclick="new_kb_group(); return false;" class="btn btn-primary pull-left display-block">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_group'); ?>
                    </a>
                    <?php } ?>
                    <a href="<?php echo admin_url('knowledge_base'); ?>"
                        class="btn btn-default pull-left display-block mleft5">
                        <?php echo _l('als_all_articles'); ?>
                    </a>
                    <div class="clearfix"></div>
                </div>

                <div class="panel_s">
                    <div class="panel-body ">
                        <?php if (count($groups) > 0) { ?>
                        <div class="panel-table-full">
                            <table class="table dt-table">
                                <thead>
                                    <th><?php echo _l('group_table_name_heading'); ?></th>
                                    <th><?php echo _l('group_table_isactive_heading'); ?></th>
                                    <th><?php echo _l('options'); ?></th>
                                </thead>
                                <tbody>
                                    <?php foreach ($groups as $group) { ?>
                                    <tr>
                                        <td><?php echo $group['name']; ?> <span
                                                class="badge mleft5"><?php echo total_rows(db_prefix() . 'knowledge_base', 'articlegroup=' . $group['groupid']); ?></span>
                                        </td>
                                        <td>
                                            <div class="onoffswitch">
                                                <input type="checkbox" id="<?php echo $group['groupid']; ?>"
                                                    data-id="<?php echo $group['groupid']; ?>"
                                                    class="onoffswitch-checkbox" <?php if (!has_permission('knowledge_base', '', 'edit')) {
    echo 'disabled';
} ?> data-switch-url="<?php echo admin_url(); ?>knowledge_base/change_group_status" <?php if ($group['active'] == 1) {
    echo 'checked';
} ?>>
                                                <label class="onoffswitch-label"
                                                    for="<?php echo $group['groupid']; ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                <?php if (has_permission('knowledge_base', '', 'edit')) { ?>
                                                <a href="#"
                                                    onclick="edit_kb_group(this,<?php echo $group['groupid']; ?>); return false"
                                                    data-name="<?php echo $group['name']; ?>"
                                                    data-color="<?php echo $group['color']; ?>"
                                                    data-description="<?php echo clear_textarea_breaks($group['description']); ?>"
                                                    data-order="<?php echo $group['group_order']; ?>"
                                                    data-active="<?php echo $group['active']; ?>"
                                                    data-slug="<?php echo $group['group_slug']; ?>"
                                                    class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                    <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                                </a>
                                                <?php } ?>
                                                <?php if (has_permission('knowledge_base', '', 'delete')) { ?>
                                                <a href="<?php echo admin_url('knowledge_base/delete_group/' . $group['groupid']); ?>"
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
                        </div>
                        <?php } else { ?>
                        <p class="no-margin"><?php echo _l('kb_no_groups_found'); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('admin/knowledge_base/group'); ?>
<?php init_tail(); ?>
</body>

</html>