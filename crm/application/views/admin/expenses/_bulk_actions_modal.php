<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade bulk_actions" id="expenses_bulk_actions" tabindex="-1" role="dialog" data-table=".table-expenses">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
            </div>
            <div class="modal-body">
                <?php if (staff_can('delete', 'expenses')) { ?>
                    <div class="checkbox checkbox-danger">
                        <input type="checkbox" name="mass_delete" id="mass_delete">
                        <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                    </div>
                    <hr class="mass_delete_separator" />
                <?php } ?>
                <div id="bulk_change">
                    <?php if (staff_can('edit', 'expenses')) { ?>
                        <?php
                        echo render_input('expenses_bulk_amount', 'expense_add_edit_amount', '', 'number');
                        echo render_select('expenses_bulk_category', $categories, ['id', 'name'], 'expense_category');
                        echo render_date_input('expenses_bulk_date', 'expense_add_edit_date');
                        echo render_select('expenses_bulk_paymentmode', $payment_modes, ['id', 'name'], 'payment_mode');
                        ?>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <a href="#" class="btn btn-primary" onclick="expenses_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    function expenses_bulk_action(event) {
        if (confirm_delete()) {
            var ids = [],
                data = {},
                mass_delete = $('#mass_delete').prop('checked');
            if (mass_delete == false || typeof(mass_delete) == 'undefined') {
                data.amount = $('#expenses_bulk_amount').val();
                data.date = $('#expenses_bulk_date').val();

                var category = $('#expenses_bulk_category');
                data.category = category.length ? category.selectpicker('val') : '';

                var paymentmode = $('#expenses_bulk_paymentmode');
                data.paymentmode = paymentmode.length ? paymentmode.selectpicker('val') : '';

                if (data.amount === '' &&
                    data.date === '' &&
                    data.category === '' &&
                    data.paymentmode === '') {
                    return;
                }
            } else {
                data.mass_delete = true;
            }
            var rows = $($('#expenses_bulk_actions').attr('data-table')).find('tbody tr');
            $.each(rows, function() {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') === true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            $(event).addClass('disabled');
            setTimeout(function() {
                $.post(admin_url + 'expenses/bulk_action', data).done(function() {
                    window.location.reload();
                });
            }, 200);
        }
    }
</script>
