<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('staff', '', 'create')) { ?>
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('staff/member'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_staff'); ?>
                    </a>
                </div>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $table_data = [
                            _l('staff_dt_name'),
                            _l('staff_dt_email'),
                            _l('role'),
                            _l('staff_dt_last_Login'),
                            _l('staff_dt_active'),
                        ];
                        $custom_fields = get_custom_fields('staff', ['show_on_table' => 1]);
                        foreach ($custom_fields as $field) {
                            array_push($table_data, [
                                'name'     => $field['name'],
                                'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                            ]);
                        }
                        render_datatable($table_data, 'staff');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_staff" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('staff/delete', ['delete_staff_form'])); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_staff'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_staff_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', $staff_members, ['staffid', ['firstname', 'lastname']], 'staff_member', get_staff_user_id(), [], [], '', '', false);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-danger _delete"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-staff', window.location.href);
});

function delete_staff_member(id) {
    $('#delete_staff').modal('show');
    $('#transfer_data_to').find('option').prop('disabled', false);
    $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
    $('#delete_staff .delete_id input').val(id);
    $('#transfer_data_to').selectpicker('refresh');
}
</script>
</body>

</html>