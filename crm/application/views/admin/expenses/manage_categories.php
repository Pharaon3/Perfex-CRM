<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="#" onclick="new_category(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_expense_category'); ?>
                    </a>
                </div>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([_l('id'), _l('name'), _l('dt_expense_description'), _l('options')], 'expenses-categories'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/expenses/expense_category'); ?>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-expenses-categories', window.location.href, [3], [3], 'undefined', [1, 'asc']);
});
</script>
</body>

</html>