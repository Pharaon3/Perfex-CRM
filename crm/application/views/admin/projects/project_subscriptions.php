<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s panel-table-full">
    <div class="panel-body">
        <?php $this->load->view('admin/subscriptions/table_html', ['url' => admin_url('subscriptions/table?project_id=' . $project->id)]); ?>
    </div>
</div>