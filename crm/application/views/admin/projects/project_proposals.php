<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s panel-table-full">
    <div class="panel-body">
        <div class="project_proposals">
            <?php $this->load->view('admin/proposals/list_template'); ?>
        </div>
    </div>
</div>

<?php
hooks()->add_action('app_admin_footer', function () {
    ?>
<script>
$(function() {
    var Proposals_ServerParams = {};
    $.each($('._hidden_inputs._filters input'), function() {
        Proposals_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
    });
    initDataTable('.table-proposals', admin_url + 'proposals/table', ['undefined'], ['undefined'],
        Proposals_ServerParams, [7, 'desc']);
    init_proposal();
})
</script>
<?php
}) ?>