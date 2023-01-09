<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="panel-table-full">
                <?php $this->load->view('admin/proposals/list_template'); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<script>
var hidden_columns = [4, 5, 6, 7, 8];
</script>
<?php init_tail(); ?>
<div id="convert_helper"></div>
<script>
var proposal_id;
$(function() {
    var Proposals_ServerParams = {};
    $.each($('._hidden_inputs._filters input'), function() {
        Proposals_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
    });
    initDataTable('.table-proposals', admin_url + 'proposals/table', ['undefined'], ['undefined'],
        Proposals_ServerParams, [7, 'desc']);
    init_proposal();
});
</script>
</body>

</html>