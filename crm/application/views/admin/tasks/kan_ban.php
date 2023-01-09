<?php defined('BASEPATH') or exit('No direct script access allowed');

$where = [];
if ($this->input->get('project_id')) {
    $where['rel_id']   = $this->input->get('project_id');
    $where['rel_type'] = 'project';
}
foreach ($task_statuses as $status) {
    $kanBan = new \app\services\tasks\TasksKanban($status['id']);
    $kanBan->search($this->input->get('search'))
            ->sortBy($this->input->get('sort_by'), $this->input->get('sort'))
            ->forProject($this->input->get('project_id'));

    if ($this->input->get('refresh')) {
        $kanBan->refresh($this->input->get('refresh')[$status['id']] ?? null);
    }

    $tasks       = $kanBan->get();
    $total_tasks = count($tasks);
    $total_pages = $kanBan->totalPages(); ?>
<ul class="kan-ban-col tasks-kanban" data-col-status-id="<?php echo $status['id']; ?>"
    data-total-pages="<?php echo $total_pages; ?>" data-total="<?php echo $total_tasks; ?>">
    <li class="kan-ban-col-wrapper">
        <div class="border-right panel_s">
            <div class="panel-heading"
                style="background:<?php echo $status['color']; ?>;border-color:<?php echo $status['color']; ?>;color:#fff; ?>"
                data-status-id="<?php echo $status['id']; ?>">

                <?php echo format_task_status($status['id'], false, true); ?> -
                <span class="tw-text-sm">
                    <?php echo $kanBan->countAll() . ' ' . _l('tasks') ?>
                </span>

            </div>
            <div class="kan-ban-content-wrapper">
                <div class="kan-ban-content">
                    <ul class="status tasks-status sortable relative"
                        data-task-status-id="<?php echo $status['id']; ?>">
                        <?php
              foreach ($tasks as $task) {
                  if ($task['status'] == $status['id']) {
                      $this->load->view('admin/tasks/_kan_ban_card', ['task' => $task, 'status' => $status['id']]);
                  }
              } ?>
                        <?php if ($total_tasks > 0) { ?>
                        <li class="text-center not-sortable kanban-load-more"
                            data-load-status="<?php echo $status['id']; ?>">
                            <a href="#" class="btn btn-default btn-block<?php if ($total_pages <= 1 || $kanBan->getPage() == $total_pages) {
                  echo ' disabled';
              } ?>" data-page="<?php echo $kanBan->getPage(); ?>"
                                onclick="kanban_load_more(<?php echo $status['id']; ?>,this,'tasks/tasks_kanban_load_more',265,360); return false;"
                                ;><?php echo _l('load_more'); ?></a>
                        </li>
                        <?php } ?>
                        <li class="text-center not-sortable mtop30 kanban-empty<?php if ($total_tasks > 0) {
                  echo ' hide';
              } ?>">
                            <h4>
                                <i class="fa-solid fa-circle-notch" aria-hidden="true"></i><br /><br />
                                <?php echo _l('no_tasks_found'); ?>
                            </h4>
                        </li>
                    </ul>
                </div>
            </div>
    </li>
</ul>
<?php
} ?>