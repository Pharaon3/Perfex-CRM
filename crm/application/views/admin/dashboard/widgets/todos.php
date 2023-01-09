<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('home_my_todo_items'); ?>">
    <div class="panel_s todo-panel">
        <div class="panel-body padding-10">
            <div class="widget-dragger"></div>
            <div class="tw-flex tw-justify-between tw-items-center tw-p-1.5">
                <p class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>

                    <span class="tw-text-neutral-700">
                        <?php echo _l('home_my_todo_items'); ?>
                    </span>
                </p>
                <div class="tw-divide-x tw-divide-solid tw-divide-neutral-300 tw-space-x-2">
                    <a href="<?php echo admin_url('todo'); ?>" class="tw-text-sm tw-mb-0">
                        <?php echo _l('home_widget_view_all'); ?>
                    </a>
                    <a href="#__todo" data-toggle="modal" class="tw-text-sm tw-mb-0 tw-pl-2">
                        <?php echo _l('new_todo'); ?>
                    </a>
                </div>
            </div>

            <hr class="-tw-mx-3 tw-mt-2 tw-mb-6">

            <?php $total_todos = count($todos); ?>
            <h4 class="todo-title text-warning tw-text-lg -tw-mt-2">
                <i class="fa fa-warning"></i>
                <?php echo _l('home_latest_todos'); ?>
            </h4>
            <ul class="list-unstyled todo unfinished-todos todos-sortable sortable">
                <?php foreach ($todos as $todo) { ?>
                <li>
                    <?php echo form_hidden('todo_order', $todo['item_order']); ?>
                    <?php echo form_hidden('finished', 0); ?>
                    <div class="media tw-mt-2">
                        <div class="media-left no-padding-right">
                            <div class="dragger todo-dragger"></div>
                            <div class="checkbox checkbox-default todo-checkbox">
                                <input type="checkbox" name="todo_id" value="<?php echo $todo['todoid']; ?>">
                                <label></label>
                            </div>
                        </div>
                        <div class="media-body">
                            <p class="todo-description read-more no-padding-left"
                                data-todo-description="<?php echo $todo['todoid']; ?>">
                                <?php echo $todo['description']; ?>
                            </p>
                            <a href="#" onclick="delete_todo_item(this,<?php echo $todo['todoid']; ?>); return false;"
                                class="pull-right text-muted">
                                <i class="fa fa-remove"></i>
                            </a>
                            <a href="#" onclick="edit_todo_item(<?php echo $todo['todoid']; ?>); return false;"
                                class="pull-right text-muted mright5">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <span class="todo-date tw-text-sm tw-text-neutral-500">
                                <?php echo $todo['dateadded']; ?>
                            </span>
                        </div>
                    </div>
                </li>
                <?php } ?>
                <li class="padding no-todos ui-state-disabled <?php if ($total_todos > 0) {
    echo 'hide';
} ?>"><?php echo _l('home_no_latest_todos'); ?></li>
            </ul>
            <?php $total_finished_todos = count($todos_finished); ?>
            <h4 class="todo-title text-success tw-mt-4 tw-text-lg tw-mb-2">
                <i class="fa fa-check"></i>
                <?php echo _l('home_latest_finished_todos'); ?>
            </h4>
            <ul class="list-unstyled todo finished-todos todos-sortable sortable">
                <?php foreach ($todos_finished as $todo_finished) { ?>
                <li>
                    <?php echo form_hidden('todo_order', $todo_finished['item_order']); ?>
                    <?php echo form_hidden('finished', 1); ?>
                    <div class="media tw-mt-2">
                        <div class="media-left no-padding-right">
                            <div class="dragger todo-dragger"></div>
                            <div class="checkbox checkbox-default todo-checkbox">
                                <input type="checkbox" value="<?php echo $todo_finished['todoid']; ?>" name="todo_id"
                                    checked>
                                <label></label>
                            </div>
                        </div>
                        <div class="media-body">
                            <p class="todo-description read-more line-throught no-padding-left">
                                <?php echo $todo_finished['description']; ?>
                            </p>
                            <a href="#"
                                onclick="delete_todo_item(this,<?php echo $todo_finished['todoid']; ?>); return false;"
                                class="pull-right text-muted"><i class="fa fa-remove"></i></a>
                            <a href="#" onclick="edit_todo_item(<?php echo $todo_finished['todoid']; ?>); return false;"
                                class="pull-right text-muted mright5">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <span class="todo-date todo-date-finished tw-text-sm tw-text-neutral-500">
                                <?php echo $todo_finished['datefinished']; ?>
                            </span>
                        </div>
                    </div>
                </li>
                <?php } ?>
                <li class="padding no-todos ui-state-disabled <?php if ($total_finished_todos > 0) {
    echo 'hide';
} ?>"><?php echo _l('home_no_finished_todos_found'); ?></li>
            </ul>
        </div>
    </div>
    <?php $this->load->view('admin/todos/_todo.php'); ?>
</div>