<?php defined('BASEPATH') or exit('No direct script access allowed');
$i                   = 0;
$has_permission_edit = has_permission('estimates', '', 'edit');
foreach ($estimate_statuses as $status) {
    $kanBan = new \app\services\estimates\EstimatesPipeline($status);
    $kanBan->search($this->input->get('search'))
    ->sortBy($this->input->get('sort_by'), $this->input->get('sort'));
    if ($this->input->get('refresh')) {
        $kanBan->refresh($this->input->get('refresh')[$status] ?? null);
    }
    $estimates       = $kanBan->get();
    $total_estimates = count($estimates);
    $total_pages     = $kanBan->totalPages(); ?>
<ul class="kan-ban-col" data-col-status-id="<?php echo $status; ?>" data-total-pages="<?php echo $total_pages; ?>"
    data-total="<?php echo $total_estimates; ?>">
    <li class="kan-ban-col-wrapper">
        <div class="panel_s panel-<?php echo estimate_status_color_class($status); ?> no-mbot">
            <div class="panel-heading">
                <?php echo estimate_status_by_id($status); ?> -
                <span class="tw-text-sm">
                    <?php echo $kanBan->countAll() . ' ' . _l('estimates') ?>
                </span>
            </div>
            <div class="kan-ban-content-wrapper">
                <div class="kan-ban-content">
                    <ul class="sortable<?php if ($has_permission_edit) {
        echo ' status pipeline-status';
    } ?>" data-status-id="<?php echo $status; ?>">
                        <?php
            foreach ($estimates as $estimate) {
                $this->load->view('admin/estimates/pipeline/_kanban_card', ['estimate' => $estimate, 'status' => $status]);
            } ?>
                        <?php if ($total_estimates > 0) { ?>
                        <li class="text-center not-sortable kanban-load-more" data-load-status="<?php echo $status; ?>">
                            <a href="#" class="btn btn-default btn-block<?php if ($total_pages <= 1 || $kanBan->getPage() === $total_pages) {
                echo ' disabled';
            } ?>" data-page="<?php echo $kanBan->getPage(); ?>"
                                onclick="kanban_load_more(<?php echo $status; ?>,this,'estimates/pipeline_load_more',310,360); return false;"
                                ;><?php echo _l('load_more'); ?></a>
                        </li>
                        <?php } ?>
                        <li class="text-center not-sortable mtop30 kanban-empty<?php if ($total_estimates > 0) {
                echo ' hide';
            } ?>">
                            <h4>
                                <i class="fa-solid fa-circle-notch" aria-hidden="true"></i><br /><br />
                                <?php echo _l('no_estimates_found'); ?>
                            </h4>
                        </li>
                    </ul>
                </div>
            </div>
    </li>
</ul>
<?php $i++;
} ?>