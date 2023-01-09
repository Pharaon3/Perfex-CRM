<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="p_buttons">
    <?php $this->load->view('admin/contracts/filters'); ?>
</div>
<div class="clearfix"></div>
<div class="panel_s panel-table-full tw-mt-4">
    <div class="panel-body">
        <div class="project_contracts">
            <?php $this->load->view('admin/contracts/table_html'); ?>
        </div>
    </div>
</div>