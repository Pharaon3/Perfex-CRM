<?php defined('BASEPATH') or exit('No direct script access allowed');
$i = 0;
foreach ($statuses as $status) {
    $kanBan = new \app\services\proposals\ProposalsPipeline($status);
    $kanBan->search($this->input->get('search'))
    ->sortBy($this->input->get('sort_by'), $this->input->get('sort'));
    if ($this->input->get('refresh')) {
        $kanBan->refresh($this->input->get('refresh')[$status] ?? null);
    }
    $proposals       = $kanBan->get();
    $total_proposals = count($proposals);
    $total_pages     = $kanBan->totalPages(); ?>
<ul class="kan-ban-col" data-col-status-id="<?php echo $status; ?>" data-total-pages="<?php echo $total_pages; ?>"
    data-total="<?php echo $total_proposals; ?>">
    <li class="kan-ban-col-wrapper">
        <div class="panel_s panel-<?php echo proposal_status_color_class($status); ?> no-mbot">
            <div class="panel-heading">
                <?php echo format_proposal_status($status, '', false); ?> -
                <span class="tw-text-sm">
                    <?php echo $kanBan->countAll() . ' ' . _l('proposals') ?>
                </span>
            </div>
            <div class="kan-ban-content-wrapper">
                <div class="kan-ban-content">
                    <ul class="sortable<?php if (has_permission('proposals', '', 'edit')) {
        echo ' status pipeline-status';
    } ?>" data-status-id="<?php echo $status; ?>">
                        <?php
          foreach ($proposals as $proposal) {
              $this->load->view('admin/proposals/pipeline/_kanban_card', ['proposal' => $proposal, 'status' => $status]);
          } ?>
                        <?php if ($total_proposals > 0) { ?>
                        <li class="text-center not-sortable kanban-load-more" data-load-status="<?php echo $status; ?>">
                            <a href="#" class="btn btn-default btn-block<?php if ($total_pages <= 1 || $kanBan->getPage() === $total_pages) {
              echo ' disabled';
          } ?>" data-page="<?php echo $kanBan->getPage(); ?>"
                                onclick="kanban_load_more(<?php echo $status; ?>,this,'proposals/pipeline_load_more',347,360); return false;"
                                ;><?php echo _l('load_more'); ?></a>
                        </li>
                        <?php } ?>
                        <li class="text-center not-sortable mtop30 kanban-empty<?php if ($total_proposals > 0) {
              echo ' hide';
          } ?>">
                            <h4>
                                <i class="fa-solid fa-circle-notch" aria-hidden="true"></i><br /><br />
                                <?php echo _l('no_proposals_found'); ?>
                            </h4>
                        </li>
                    </ul>
                </div>
            </div>
    </li>
</ul>
<?php $i++;
} ?>