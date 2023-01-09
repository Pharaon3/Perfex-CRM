<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php set_ticket_open($ticket->adminread, $ticket->ticketid); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($ticket->merged_ticket_id !== null) { ?>
                <div class="alert alert-info" role="alert">
                    <div class="tw-flex tw-justify-between tw-items-center">
                        <p class="tw-font-semibold tw-mb-0">
                            <?php echo _l('ticket_merged_notice'); ?>:
                            <?php echo $ticket->merged_ticket_id; ?>
                        </p>
                        <a href="<?php echo admin_url('tickets/ticket/' . $ticket->merged_ticket_id); ?>"
                            class="btn btn-info btn-sm">
                            <?php echo _l('view_primary_ticket'); ?>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <div class="tw-mb-4">
                    <div class="md:tw-flex md:tw-items-center">
                        <div class="tw-inline-flex tw-items-center tw-grow md:tw-mr-4">
                            <h3 class="tw-font-semibold tw-text-xl tw-my-0">
                                <span id="ticket_subject">
                                    #<?php echo $ticket->ticketid; ?> - <?php echo $ticket->subject; ?>
                                </span>
                            </h3>
                            <?php echo '<span class="tw-self-start md:tw-self-center label' . (is_mobile() ? ' ' : ' mleft15 ') . 'single-ticket-status-label" style="color:' . $ticket->statuscolor . ';border: 1px solid ' . adjust_hex_brightness($ticket->statuscolor, 0.4) . '; background:' . adjust_hex_brightness($ticket->statuscolor, 0.04) . ';">' . ticket_status_translate($ticket->ticketstatusid) . '</span>'; ?>

                        </div>
                        <?php
                        echo render_select('status_top', $statuses, ['ticketstatusid', 'name'], '', $ticket->status, [], [], 'no-mbot tw-flex-1 tw-max-w-sm', '', false);
                        ?>
                    </div>
                    <?php
                                if ($ticket->project_id != 0) {
                                    echo '<p class="tw-text-base tw-font-normal tw-mb-0">' . _l('ticket_linked_to_project', '<a href="' . admin_url('projects/view/' . $ticket->project_id) . '">' . get_project_name_by_id($ticket->project_id) . '</a>') . '</p>';
                                }
                            ?>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                                <div class="horizontal-tabs">
                                    <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                        <li role="presentation" class="<?php if (!$this->session->flashdata('active_tab')) {
                                echo 'active';
                            } ?>">
                                            <a href="#addreply" aria-controls="addreply" role="tab" data-toggle="tab">
                                                <?php echo _l('ticket_single_add_reply'); ?>
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#note" aria-controls="note" role="tab" data-toggle="tab">
                                                <?php echo _l('ticket_single_add_note'); ?>
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#tab_reminders"
                                                onclick="initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + <?php echo $ticket->ticketid ; ?> + '/' + 'ticket', undefined, undefined, undefined,[1,'asc']); return false;"
                                                aria-controls="tab_reminders" role="tab" data-toggle="tab">
                                                <?php echo _l('ticket_reminders'); ?>
                                                <?php
                                 $total_reminders = total_rows(
                                db_prefix() . 'reminders',
                                [
                                     'isnotified' => 0,
                                     'staff'      => get_staff_user_id(),
                                     'rel_type'   => 'ticket',
                                     'rel_id'     => $ticket->ticketid,
                                  ]
                            );
                                 if ($total_reminders > 0) {
                                     echo '<span class="badge">' . $total_reminders . '</span>';
                                 }
                                ?>
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#othertickets" onclick="init_table_tickets(true);"
                                                aria-controls="othertickets" role="tab" data-toggle="tab">
                                                <?php echo _l('ticket_single_other_user_tickets'); ?>
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#tasks"
                                                onclick="init_rel_tasks_table(<?php echo $ticket->ticketid; ?>,'ticket'); return false;"
                                                aria-controls="tasks" role="tab" data-toggle="tab">
                                                <?php echo _l('tasks'); ?>
                                            </a>
                                        </li>
                                        <li role="presentation" class="<?php if ($this->session->flashdata('active_tab_settings')) {
                                    echo 'active';
                                } ?>">
                                            <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                                <?php echo _l('ticket_single_settings'); ?>
                                            </a>
                                        </li>
                                        <?php hooks()->do_action('add_single_ticket_tab_menu_item', $ticket); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane <?php if (!$this->session->flashdata('active_tab')) {
                                    echo 'active';
                                } ?>" id="addreply">
                                <?php $tags = get_tags_in($ticket->ticketid, 'ticket'); ?>
                                <?php if (count($tags) > 0) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo '<p><i class="fa fa-tag" aria-hidden="true"></i> ' . _l('tags') . ':</p> ' . render_tags($tags); ?>
                                        <hr class="hr-panel-separator" />
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if (count($ticket->ticket_notes) > 0) { ?>
                                <div class="mbot15">
                                    <h4 class="tw-font-semibold tw-text-base tw-mt-0">
                                        <?php echo _l('ticket_single_private_staff_notes'); ?>
                                    </h4>
                                    <div class="ticketstaffnotes tw-mb-1 tw-inline-block tw-w-full">
                                        <?php foreach ($ticket->ticket_notes as $note) { ?>
                                        <div
                                            class="tw-rounded-md tw-bg-warning-50 tw-p-4 tw-mb-2 tw-group tw-border tw-border-solid tw-border-warning-100">
                                            <div class="tw-flex">
                                                <div class="tw-flex-shrink-0">
                                                    <?php echo staff_profile_image($note['addedfrom'], ['staff-profile-xs-image']); ?>
                                                </div>
                                                <div class="tw-ml-2 tw-flex-1">
                                                    <div class="tw-flex">
                                                        <h3
                                                            class="tw-text-sm tw-font-medium tw-text-warning-800 tw-mb-0 tw-mt-1 tw-grow">
                                                            <a href="<?php echo admin_url('staff/profile/' . $note['addedfrom']); ?>"
                                                                class="tw-text-warning-700 hover:tw-text-warning-900">
                                                                <?php echo _l('ticket_single_ticket_note_by', get_staff_full_name($note['addedfrom'])); ?>
                                                            </a>
                                                            <br />
                                                            <span class="tw-text-xs tw-text-warning-600">
                                                                <?php echo _l('ticket_single_note_added', _dt($note['dateadded'])); ?>
                                                            </span>
                                                        </h3>

                                                        <?php if ($note['addedfrom'] == get_staff_user_id() || is_admin()) { ?>
                                                        <div class="tw-space-x-1 tw-hidden group-hover:tw-block">
                                                            <a href="#"
                                                                class="tw-text-warning-600 hover:tw-text-warning-700 focus:tw-text-warning-700"
                                                                onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;">
                                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                                            </a>
                                                            <a href="<?php echo admin_url('misc/delete_note/' . $note['id']); ?>"
                                                                class="tw-text-warning-600 hover:tw-text-warning-700 focus:tw-text-warning-700 _delete">
                                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                                            </a>
                                                        </div>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="tw-mt-2 tw-text-sm tw-text-warning-700">
                                                        <div data-note-description="<?php echo $note['id']; ?>">
                                                            <?php echo check_for_links($note['description']); ?>
                                                        </div>
                                                        <div data-note-edit-textarea="<?php echo $note['id']; ?>"
                                                            class="hide">
                                                            <textarea name="description" class="form-control"
                                                                rows="4"><?php echo clear_textarea_breaks($note['description']); ?></textarea>
                                                            <div class="text-right tw-mt-3">
                                                                <button type="button" class="btn btn-default"
                                                                    onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;">
                                                                    <?php echo _l('cancel'); ?>
                                                                </button>
                                                                <button type="button" class="btn btn-primary"
                                                                    onclick="edit_note(<?php echo $note['id']; ?>);">
                                                                    <?php echo _l('update_note'); ?>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <div>
                                    <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'single-ticket-form', 'novalidate' => true]); ?>
                                    <a href="<?php echo admin_url('tickets/delete/' . $ticket->ticketid); ?>"
                                        data-toggle="tooltip" data-title="<?= _l('delete', _l('ticket_lowercase')); ?>"
                                        class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete tw-mr-2">
                                        <i class="fa-regular fa-trash-can fa-lg"></i>
                                    </a>

                                    <?php if (!empty($ticket->priority_name)) { ?>
                                    <span class="ticket-label label label-default inline-block">
                                        <?php echo _l('ticket_single_priority', ticket_priority_translate($ticket->priorityid)); ?>
                                    </span>
                                    <?php } ?>
                                    <?php if (!empty($ticket->service_name)) { ?>
                                    <span class="ticket-label label label-default inline-block">
                                        <?php echo _l('service') . ': ' . $ticket->service_name; ?>
                                    </span>
                                    <?php } ?>
                                    <?php echo form_hidden('ticketid', $ticket->ticketid); ?>
                                    <span class="ticket-label label label-default inline-block">
                                        <?php echo _l('department') . ': ' . $ticket->department_name; ?>
                                    </span>
                                    <?php if ($ticket->assigned != 0) { ?>
                                    <span class="ticket-label label label-info inline-block">
                                        <?php echo _l('ticket_assigned'); ?>:
                                        <?php echo get_staff_full_name($ticket->assigned); ?>
                                    </span>
                                    <?php } ?>
                                    <?php if ($ticket->lastreply !== null) { ?>
                                    <span class="ticket-label label label-success inline-block" data-toggle="tooltip"
                                        title="<?php echo _dt($ticket->lastreply); ?>">
                                        <span class="text-has-action">
                                            <?php echo _l('ticket_single_last_reply', time_ago($ticket->lastreply)); ?>
                                        </span>
                                    </span>
                                    <?php } ?>

                                    <a class="ticket-label label label-info inline-block"
                                        href="<?php echo get_ticket_public_url($ticket); ?>" target="_blank">
                                        <?php echo _l('view_public_form'); ?>
                                    </a>

                                    <div class="mtop15">
                                        <?php
                        $use_knowledge_base = get_option('use_knowledge_base');
                        ?>
                                        <div class="row mbot15">
                                            <div class="col-md-6">
                                                <select data-width="100%" id="insert_predefined_reply"
                                                    data-live-search="true" class="selectpicker"
                                                    data-title="<?php echo _l('ticket_single_insert_predefined_reply'); ?>">
                                                    <?php foreach ($predefined_replies as $predefined_reply) { ?>
                                                    <option value="<?php echo $predefined_reply['id']; ?>">
                                                        <?php echo $predefined_reply['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <?php if ($use_knowledge_base == 1) { ?>
                                            <div class="visible-xs">
                                                <div class="mtop15"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <?php $groups = get_all_knowledge_base_articles_grouped(); ?>
                                                <select data-width="100%" id="insert_knowledge_base_link"
                                                    class="selectpicker" data-live-search="true"
                                                    onchange="insert_ticket_knowledgebase_link(this);"
                                                    data-title="<?php echo _l('ticket_single_insert_knowledge_base_link'); ?>">
                                                    <option value=""></option>
                                                    <?php foreach ($groups as $group) { ?>
                                                    <?php if (count($group['articles']) > 0) { ?>
                                                    <optgroup label="<?php echo $group['name']; ?>">
                                                        <?php foreach ($group['articles'] as $article) { ?>
                                                        <option value="<?php echo $article['articleid']; ?>">
                                                            <?php echo $article['subject']; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </optgroup>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php echo render_textarea('message', '', '', [], [], '', 'tinymce'); ?>
                                        <div
                                            class="alert alert-warning staff_replying_notice <?php echo ($ticket->staff_id_replying === null || $ticket->staff_id_replying === get_staff_user_id()) ? 'hide' : '' ?>">
                                            <?php if ($ticket->staff_id_replying !== null && $ticket->staff_id_replying !== get_staff_user_id()) { ?>
                                            <p><?php echo _l('staff_is_currently_replying', get_staff_full_name($ticket->staff_id_replying)); ?>
                                            </p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="ticket-reply-tools">
                                        <?php if ($ticket->merged_ticket_id === null) { ?>
                                        <div class="btn-bottom-toolbar text-right">
                                            <button type="submit" class="btn btn-primary"
                                                data-form="#single-ticket-form" autocomplete="off"
                                                data-loading-text="<?php echo _l('wait_text'); ?>">
                                                <?php echo _l('ticket_single_add_response'); ?>
                                            </button>
                                        </div>
                                        <?php } ?>
                                        <div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <?php echo render_select('status', $statuses, ['ticketstatusid', 'name'], 'ticket_single_change_status', get_option('default_ticket_reply_status'), [], [], '', '', false); ?>
                                                    <?php echo render_input('cc', 'CC', $ticket->cc); ?>
                                                    <?php if ($ticket->assigned !== get_staff_user_id()) { ?>
                                                    <div class="checkbox">
                                                        <input type="checkbox" name="assign_to_current_user"
                                                            id="assign_to_current_user">
                                                        <label
                                                            for="assign_to_current_user"><?php echo _l('ticket_single_assign_to_me_on_update'); ?></label>
                                                    </div>
                                                    <?php } ?>
                                                    <div class="checkbox">
                                                        <input type="checkbox"
                                                            <?php echo hooks()->apply_filters('ticket_add_response_and_back_to_list_default', 'checked'); ?>
                                                            name="ticket_add_response_and_back_to_list" value="1"
                                                            id="ticket_add_response_and_back_to_list">
                                                        <label
                                                            for="ticket_add_response_and_back_to_list"><?php echo _l('ticket_add_response_and_back_to_list'); ?></label>
                                                    </div>
                                                </div>
                                                <?php
                               $totalMergedTickets = count($merged_tickets);
                               if ($totalMergedTickets > 0) { ?>
                                                <div class="col-md-7">
                                                    <div class="mtop25">
                                                        <p class="alert alert-info">
                                                            <?php echo _l('ticket_merged_tickets_header', $totalMergedTickets) ?>
                                                        </p>
                                                        <ul class="list-group">
                                                            <?php foreach ($merged_tickets as $merged_ticket) { ?>
                                                            <a href="<?php echo admin_url('tickets/ticket/' . $merged_ticket['ticketid']) ?>"
                                                                class="list-group-item tw-font-medium">
                                                                #<?php echo $merged_ticket['ticketid'] ?> -
                                                                <?php echo $merged_ticket['subject'] ?>
                                                            </a>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <hr class="hr-panel-separator" />
                                            <div class="row attachments">
                                                <div class="attachment">
                                                    <div class="col-md-5 mbot15">
                                                        <div class="form-group">
                                                            <label for="attachment" class="control-label">
                                                                <?php echo _l('ticket_single_attachments'); ?>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="file"
                                                                    extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>"
                                                                    filesize="<?php echo file_upload_max_size(); ?>"
                                                                    class="form-control" name="attachments[0]"
                                                                    accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default add_more_attachments"
                                                                        data-max="<?php echo get_option('maximum_allowed_ticket_attachments'); ?>"
                                                                        type="button">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="note">
                                <hr class="no-mtop" />
                                <div class="form-group">
                                    <label
                                        for="note_description"><?php echo _l('ticket_single_note_heading'); ?></label>
                                    <textarea class="form-control" name="note_description" rows="5"></textarea>
                                </div>
                                <a
                                    class="btn btn-primary pull-right add_note_ticket"><?php echo _l('ticket_single_add_note'); ?></a>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab_reminders">
                                <a href="#" class="btn btn-default" data-toggle="modal"
                                    data-target=".reminder-modal-ticket-<?php echo $ticket->ticketid; ?>"><i
                                        class="fa-regular fa-bell"></i>
                                    <?php echo _l('ticket_set_reminder_title'); ?></a>
                                <hr />
                                <?php render_datatable([ _l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')], 'reminders'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="othertickets">
                                <hr class="no-mtop" />
                                <div class="_filters _hidden_inputs hidden tickets_filters">
                                    <?php echo form_hidden('filters_ticket_id', $ticket->ticketid); ?>
                                    <?php echo form_hidden('filters_email', $ticket->email); ?>
                                    <?php echo form_hidden('filters_userid', $ticket->userid); ?>
                                </div>
                                <?php echo AdminTicketsTableStructure(); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tasks">
                                <hr class="no-mtop" />
                                <?php init_relation_tasks_table(['data-new-rel-id' => $ticket->ticketid, 'data-new-rel-type' => 'ticket']); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane <?php if ($this->session->flashdata('active_tab_settings')) {
                                   echo 'active';
                               } ?>" id="settings">
                                <hr class="no-mtop" />
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo render_input('subject', 'ticket_settings_subject', $ticket->subject); ?>
                                        <div class="form-group select-placeholder">
                                            <label for="contactid"
                                                class="control-label"><?php echo _l('contact'); ?></label>
                                            <select name="contactid" id="contactid" class="ajax-search"
                                                data-width="100%" data-live-search="true"
                                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" <?php if (!$ticket->userid) {
                                   echo ' data-no-contact="true"';
                               } else {
                                   echo ' data-ticket-emails="' . $ticket->ticket_emails . '"';
                               } ?>>
                                                <?php
                              $rel_data = get_relation_data('contact', $ticket->contactid);
                              $rel_val  = get_relation_values($rel_data, 'contact');
                              echo '<option value="' . $rel_val['id'] . '" selected data-subtext="' . $rel_val['subtext'] . '">' . $rel_val['name'] . '</option>';
                              ?>
                                            </select>
                                            <?php echo form_hidden('userid', $ticket->userid); ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo render_input('name', 'ticket_settings_to', $ticket->submitter, 'text', ['disabled' => true]); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php
                              if ($ticket->userid != 0) {
                                  echo render_input('email', 'ticket_settings_email', $ticket->email, 'email', ['disabled' => true]);
                              } else {
                                  echo render_input('email', 'ticket_settings_email', $ticket->ticket_email, 'email', ['disabled' => true]);
                              }
                             ?>
                                            </div>
                                        </div>
                                        <?php echo render_select('department', $departments, ['departmentid', 'name'], 'ticket_settings_departments', $ticket->department); ?>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group select-placeholder">
                                            <label for="assigned" class="control-label">
                                                <?php echo _l('ticket_settings_assign_to'); ?>
                                            </label>
                                            <select name="assigned" data-live-search="true" id="assigned"
                                                class="form-control selectpicker"
                                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                <option value=""><?php echo _l('ticket_settings_none_assigned'); ?>
                                                </option>
                                                <?php foreach ($staff as $member) {
                                 // Ticket is assigned to member
                                 // Member is set to inactive
                                 // We should show the member in the dropdown too
                                 // Otherwise, skip this member
                                 if ($member['active'] == 0 && $ticket->assigned != $member['staffid']) {
                                     continue;
                                 } ?>
                                                <option value="<?php echo $member['staffid']; ?>" <?php if ($ticket->assigned == $member['staffid']) {
                                     echo 'selected';
                                 } ?>>
                                                    <?php echo $member['firstname'] . ' ' . $member['lastname'] ; ?>
                                                </option>
                                                <?php
                             } ?>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-<?php if (get_option('services') == 1) {
                                 echo 6;
                             } else {
                                 echo 12;
                             } ?>">
                                                <?php
                           $priorities['callback_translate'] = 'ticket_priority_translate';
                           echo render_select('priority', $priorities, ['priorityid', 'name'], 'ticket_settings_priority', $ticket->priority); ?>
                                            </div>
                                            <?php if (get_option('services') == 1) { ?>
                                            <div class="col-md-6">
                                                <?php if (is_admin() || get_option('staff_members_create_inline_ticket_services') == '1') {
                               echo render_select_with_input_group('service', $services, ['serviceid', 'name'], 'ticket_settings_service', $ticket->service, '<div class="input-group-btn"><a href="#" class="btn btn-default" onclick="new_service();return false;"><i class="fa fa-plus"></i></a></div>');
                           } else {
                               echo render_select('service', $services, ['serviceid', 'name'], 'ticket_settings_service', $ticket->service);
                           }
                              ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group select-placeholder projects-wrapper<?php if ($ticket->userid == 0) {
                                  echo ' hide';
                              } ?>">
                                            <label for="project_id"><?php echo _l('project'); ?></label>
                                            <div id="project_ajax_search_wrapper">
                                                <select name="project_id" id="project_id" class="projects ajax-search"
                                                    data-live-search="true" data-width="100%"
                                                    data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                    <?php if ($ticket->project_id != 0) { ?>
                                                    <option value="<?php echo $ticket->project_id; ?>">
                                                        <?php echo get_project_name_by_id($ticket->project_id); ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php  echo render_input('merge_ticket_ids', 'merge_ticket_ids_field_label', '', 'text', $ticket->merged_ticket_id === null ? ['placeholder' => _l('merge_ticket_ids_field_placeholder')] : ['disabled' => true]); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mbot20">
                                                    <label for="tags" class="control-label"><i class="fa fa-tag"
                                                            aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
                                                    <input type="text" class="tagsinput" id="tags" name="tags"
                                                        value="<?php echo prep_tags_input(get_tags_in($ticket->ticketid, 'ticket')); ?>"
                                                        data-role="tagsinput">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <?php echo render_custom_fields('tickets', $ticket->ticketid); ?>
                                    </div>
                                </div>
                                <?php hooks()->do_action('add_single_ticket_tab_menu_content', $ticket); ?>

                                <div
                                    class="tw-bg-neutral-50 text-right tw-px-6 tw-py-3 -tw-mx-6 -tw-mb-6 tw-border-t tw-border-solid tw-border-neutral-200 tw-rounded-b-md">
                                    <a href="#" class="btn btn-primary save_changes_settings_single_ticket">
                                        <?php echo _l('submit'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel_s mtop20">
                    <div class="panel-body <?php if ($ticket->admin == null) {
                                  echo 'client-reply';
                              } ?>">
                        <div class="row">
                            <div class="col-md-3 border-right ticket-submitter-info ticket-submitter-info">
                                <p>
                                    <?php if ($ticket->admin == null || $ticket->admin == 0) { ?>
                                    <?php if ($ticket->userid != 0) { ?>
                                    <a
                                        href="<?php echo admin_url('clients/client/' . $ticket->userid . '?contactid=' . $ticket->contactid); ?>"><?php echo $ticket->submitter; ?>
                                    </a>
                                    <?php } else {
                                  echo $ticket->submitter; ?>
                                    <br />
                                    <a
                                        href="mailto:<?php echo $ticket->ticket_email; ?>"><?php echo $ticket->ticket_email; ?></a>
                                    <hr />
                                    <?php
                        if (total_rows(db_prefix() . 'spam_filters', ['type' => 'sender', 'value' => $ticket->ticket_email, 'rel_type' => 'tickets']) == 0) { ?>
                                    <button type="button" data-sender="<?php echo $ticket->ticket_email; ?>"
                                        class="btn btn-danger block-sender btn-sm"> <?php echo _l('block_sender'); ?>
                                    </button>
                                    <?php
                    } else {
                        echo '<span class="label label-danger">' . _l('sender_blocked') . '</span>';
                    }
                              }
              } else {  ?>
                                    <a
                                        href="<?php echo admin_url('profile/' . $ticket->admin); ?>"><?php echo $ticket->opened_by; ?></a>
                                    <?php } ?>
                                </p>
                                <p class="text-muted">
                                    <?php if ($ticket->admin !== null || $ticket->admin != 0) {
                  echo _l('ticket_staff_string');
              } else {
                  if ($ticket->userid != 0) {
                      echo _l('ticket_client_string');
                  }
              }
           ?>
                                </p>
                                <?php if (has_permission('tasks', '', 'create')) { ?>
                                <a href="#" class="btn btn-default btn-sm"
                                    onclick="convert_ticket_to_task(<?php echo $ticket->ticketid; ?>,'ticket'); return false;"><?php echo _l('convert_to_task'); ?></a>
                                <?php } ?>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12 text-right tw-mb-6 tw-space-x-2">
                                        <?php if (!empty($ticket->message)) { ?>
                                        <a href="#"
                                            class="tw-text-neutral-500 hover:tw-text-neutral-700 active:tw-text-neutral-600"
                                            onclick="print_ticket_message(<?php echo $ticket->ticketid; ?>, 'ticket'); return false;"
                                            class="mright5"><i class="fa fa-print"></i></a>
                                        <?php } ?>
                                        <a href="#"
                                            class="tw-text-neutral-500 hover:tw-text-neutral-700 active:tw-text-neutral-600"
                                            onclick="edit_ticket_message(<?php echo $ticket->ticketid; ?>,'ticket'); return false;"><i
                                                class="fa-regular fa-pen-to-square"></i></a>
                                    </div>
                                </div>
                                <div data-ticket-id="<?php echo $ticket->ticketid; ?>" class="tc-content">
                                    <?php echo check_for_links($ticket->message); ?>
                                </div>
                                <?php if (count($ticket->attachments) > 0) {
               echo '<hr />';
               foreach ($ticket->attachments as $attachment) {
                   $path     = get_upload_path_by_type('ticket') . $ticket->ticketid . '/' . $attachment['file_name'];
                   $is_image = is_image($path);

                   if ($is_image) {
                       echo '<div class="preview_image">';
                   } ?>
                                <a href="<?php echo site_url('download/file/ticket/' . $attachment['id']); ?>"
                                    class="display-block mbot5" <?php if ($is_image) { ?>
                                    data-lightbox="attachment-ticket-<?php echo $ticket->ticketid; ?>" <?php } ?>>
                                    <i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                                    <?php echo $attachment['file_name']; ?>
                                    <?php if ($is_image) { ?>
                                    <img class="mtop5"
                                        src="<?php echo site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $attachment['filetype']); ?>">
                                    <?php } ?>
                                </a>
                                <?php if ($is_image) {
                       echo '</div>';
                   }
                   if (is_admin() || (!is_admin() && get_option('allow_non_admin_staff_to_delete_ticket_attachments') == '1')) {
                       echo '<a href="' . admin_url('tickets/delete_attachment/' . $attachment['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                   }
                   echo '<hr />'; ?>
                                <?php
               }
           } ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <?php echo _l('ticket_posted', _dt($ticket->date)); ?>
                    </div>
                </div>
                <?php foreach ($ticket_replies as $reply) { ?>
                <div class="panel_s">
                    <div class="panel-body <?php if ($reply['admin'] == null) {
               echo 'client-reply';
           } ?>">
                        <div class="row">
                            <div class="col-md-3 border-right ticket-submitter-info">
                                <p>
                                    <?php if ($reply['admin'] == null || $reply['admin'] == 0) { ?>
                                    <?php if ($reply['userid'] != 0) { ?>
                                    <a
                                        href="<?php echo admin_url('clients/client/' . $reply['userid'] . '?contactid=' . $reply['contactid']); ?>"><?php echo $reply['submitter']; ?></a>
                                    <?php } else { ?>
                                    <?php echo $reply['submitter']; ?>
                                    <br />
                                    <a
                                        href="mailto:<?php echo $reply['reply_email']; ?>"><?php echo $reply['reply_email']; ?></a>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <a
                                        href="<?php echo admin_url('profile/' . $reply['admin']); ?>"><?php echo $reply['submitter']; ?></a>
                                    <?php } ?>
                                </p>
                                <p class="text-muted">
                                    <?php if ($reply['admin'] !== null || $reply['admin'] != 0) {
               echo _l('ticket_staff_string');
           } else {
               if ($reply['userid'] != 0) {
                   echo _l('ticket_client_string');
               }
           }
                 ?>
                                </p>
                                <hr />
                                <a href="<?php echo admin_url('tickets/delete_ticket_reply/' . $ticket->ticketid . '/' . $reply['id']); ?>"
                                    class="btn btn-danger pull-left _delete mright5 btn-sm"><?php echo _l('delete_ticket_reply'); ?></a>
                                <div class="clearfix"></div>
                                <?php if (has_permission('tasks', '', 'create')) { ?>
                                <a href="#" class="pull-left btn btn-default mtop5 btn-sm"
                                    onclick="convert_ticket_to_task(<?php echo $reply['id']; ?>,'reply'); return false;"><?php echo _l('convert_to_task'); ?>
                                </a>
                                <div class="clearfix"></div>
                                <?php } ?>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12 text-right tw-mb-6 tw-space-x-2">
                                        <?php if (!empty($reply['message'])) { ?>
                                        <a href="#"
                                            class="tw-text-neutral-500 hover:tw-text-neutral-700 active:tw-text-neutral-600"
                                            onclick="print_ticket_message(<?php echo $reply['id']; ?>, 'reply'); return false;"
                                            class="mright5"><i class="fa fa-print"></i></a>
                                        <?php } ?>
                                        <a href="#"
                                            class="tw-text-neutral-500 hover:tw-text-neutral-700 active:tw-text-neutral-600"
                                            onclick="edit_ticket_message(<?php echo $reply['id']; ?>,'reply'); return false;"><i
                                                class="fa-regular fa-pen-to-square"></i></a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div data-reply-id="<?php echo $reply['id']; ?>" class="tc-content">
                                    <?php echo check_for_links($reply['message']); ?>
                                </div>
                                <?php if (count($reply['attachments']) > 0) {
                     echo '<hr />';
                     foreach ($reply['attachments'] as $attachment) {
                         $path     = get_upload_path_by_type('ticket') . $ticket->ticketid . '/' . $attachment['file_name'];
                         $is_image = is_image($path);

                         if ($is_image) {
                             echo '<div class="preview_image">';
                         } ?>
                                <a href="<?php echo site_url('download/file/ticket/' . $attachment['id']); ?>"
                                    class="display-block mbot5" <?php if ($is_image) { ?>
                                    data-lightbox="attachment-reply-<?php echo $reply['id']; ?>" <?php } ?>>
                                    <i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                                    <?php echo $attachment['file_name']; ?>
                                    <?php if ($is_image) { ?>
                                    <img class="mtop5"
                                        src="<?php echo site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $attachment['filetype']); ?>">
                                    <?php } ?>
                                </a>
                                <?php if ($is_image) {
                             echo '</div>';
                         }
                         if (is_admin() || (!is_admin() && get_option('allow_non_admin_staff_to_delete_ticket_attachments') == '1')) {
                             echo '<a href="' . admin_url('tickets/delete_attachment/' . $attachment['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                         }
                         echo '<hr />';
                     }
                 } ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span><?php echo _l('ticket_posted', _dt($reply['date'])); ?></span>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="btn-bottom-pusher"></div>
        <?php if (count($ticket_replies) > 1) { ?>
        <a href="#top" id="toplink">↑</a>
        <a href="#bot" id="botlink">↓</a>
        <?php } ?>
    </div>
</div>
<!-- The reminders modal -->
<?php $this->load->view(
                     'admin/includes/modals/reminder',
                     [
   'id'             => $ticket->ticketid,
   'name'           => 'ticket',
   'members'        => $staff,
   'reminder_title' => _l('ticket_set_reminder_title'), ]
                 ); ?>
<!-- Edit Ticket Messsage Modal -->
<div class="modal fade" id="ticket-message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <?php echo form_open(admin_url('tickets/edit_message')); ?>
        <div class="modal-content">
            <div id="edit-ticket-message-additional"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo _l('ticket_message_edit'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo render_textarea('data', '', '', [], [], '', 'tinymce-ticket-edit'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
var _ticket_message;
</script>
<?php $this->load->view('admin/tickets/services/service'); ?>
<?php init_tail(); ?>
<?php hooks()->do_action('ticket_admin_single_page_loaded', $ticket); ?>
<script>
$(function() {
    $('#single-ticket-form').appFormValidator();
    init_ajax_search('contact', '#contactid.ajax-search', {
        tickets_contacts: true
    });
    init_ajax_search('project', 'select[name="project_id"]', {
        customer_id: function() {
            return $('input[name="userid"]').val();
        }
    });
    $('body').on('shown.bs.modal', '#_task_modal', function() {
        if (typeof(_ticket_message) != 'undefined') {
            // Init the task description editor
            if (!is_mobile()) {
                $(this).find('#description').click();
            } else {
                $(this).find('#description').focus();
            }
            setTimeout(function() {
                tinymce.get('description').execCommand('mceInsertContent', false,
                    _ticket_message);
                $('#_task_modal input[name="name"]').val($('#ticket_subject').text().trim());
            }, 100);
        }
    });
    var editorMessage = tinymce.get('message');
    if (typeof(editorMessage) != 'undefined') {
        var firstTypeCheckPerformed = false;

        editorMessage.on('change', function() {
            if (!firstTypeCheckPerformed) {
                // make AJAX Request
                $.get(admin_url + 'tickets/check_staff_replying/<?php echo $ticket->ticketid; ?>',
                    function(result) {
                        var data = JSON.parse(result)
                        if (data.is_other_staff_replying === true || data
                            .is_other_staff_replying === 'true') {
                            $('.staff_replying_notice').html('<p>' + data.message + '</p>');
                            $('.staff_replying_notice').removeClass('hide');
                        } else {
                            $('.staff_replying_notice').addClass('hide');
                        }
                    });

                firstTypeCheckPerformed = true;
            }

            $.post(admin_url +
                'tickets/update_staff_replying/<?php echo $ticket->ticketid; ?>/<?php echo get_staff_user_id()?>'
            );
        });

        $(document).on('pagehide, beforeunload', function() {
            $.post(admin_url + 'tickets/update_staff_replying/<?php echo $ticket->ticketid; ?>');
        })

        $(document).on('visibilitychange', function() {
            if (document.visibilityState === 'visible' || (editorMessage.getContent().trim() != ''))
                return;
            $.post(admin_url + 'tickets/update_staff_replying/<?php echo $ticket->ticketid; ?>');
        })
    }
});


var Ticket_message_editor;
var edit_ticket_message_additional = $('#edit-ticket-message-additional');

function edit_ticket_message(id, type) {
    edit_ticket_message_additional.empty();
    // type is either ticket or reply
    _ticket_message = $('[data-' + type + '-id="' + id + '"]').html();
    init_ticket_edit_editor();
    tinyMCE.activeEditor.setContent(_ticket_message);
    $('#ticket-message').modal('show');
    edit_ticket_message_additional.append(hidden_input('type', type));
    edit_ticket_message_additional.append(hidden_input('id', id));
    edit_ticket_message_additional.append(hidden_input('main_ticket', $('input[name="ticketid"]').val()));
}

function init_ticket_edit_editor() {
    if (typeof(Ticket_message_editor) !== 'undefined') {
        return true;
    }
    Ticket_message_editor = init_editor('.tinymce-ticket-edit');
}
<?php if (has_permission('tasks', '', 'create')) { ?>

function convert_ticket_to_task(id, type) {
    if (type == 'ticket') {
        _ticket_message = $('[data-ticket-id="' + id + '"]').html();
    } else {
        _ticket_message = $('[data-reply-id="' + id + '"]').html();
    }
    var new_task_url = admin_url +
        'tasks/task?rel_id=<?php echo $ticket->ticketid; ?>&rel_type=ticket&ticket_to_task=true';
    new_task(new_task_url);
}
<?php } ?>
</script>
</body>

</html>