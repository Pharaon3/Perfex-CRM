<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="horizontal-scrollable-tabs panel-full-width-tabs">
    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
            <li role="presentation" class="active">
                <a href="#cron_command" aria-controls="cron_command" role="tab" data-toggle="tab">Command</a>
            </li>
            <li role="presentation">
                <a href="#set_invoice" aria-controls="set_invoice" role="tab"
                    data-toggle="tab"><?php echo _l('settings_sales_cron_invoice_heading'); ?></a>
            </li>
            <li role="presentation">
                <a href="#estimates" aria-controls="estimates" role="tab"
                    data-toggle="tab"><?php echo _l('estimates'); ?></a>
            </li>
            <li role="presentation">
                <a href="#proposals" aria-controls="proposals" role="tab"
                    data-toggle="tab"><?php echo _l('proposals'); ?></a>
            </li>
            <li role="presentation">
                <a href="#expenses" aria-controls="expenses" role="tab"
                    data-toggle="tab"><?php echo _l('expenses'); ?></a>
            </li>
            <li role="presentation">
                <a href="#contracts" aria-controls="contracts" role="tab"
                    data-toggle="tab"><?php echo _l('contracts'); ?></a>
            </li>
            <li role="presentation">
                <a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab"><?php echo _l('tasks'); ?></a>
            </li>
            <li role="presentation">
                <a href="#tickets" aria-controls="tickets" role="tab" data-toggle="tab"><?php echo _l('tickets'); ?></a>
            </li>

            <?php hooks()->do_action('after_cron_settings_last_tab'); ?>

        </ul>
    </div>
</div>

<div class="tab-content mtop15">
    <div role="tabpanel" class="tab-pane active" id="cron_command">
        <div class="alert alert-info tw-mb-0">
            <span class="bold text-info">CRON COMMAND: wget -q -O-
                <?php echo site_url('cron/index' . (defined('APP_CRON_KEY') ? '/' . APP_CRON_KEY : '')); ?></span><br />
            <?php if (is_admin()) { ?>
            <a href="<?php echo admin_url('misc/run_cron_manually'); ?>">Run Cron Manually</a>
            <?php } ?>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="set_invoice">
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('inv_hour_of_day_perform_auto_operations_help'); ?>"></i>
        <?php echo render_input('settings[invoice_auto_operations_hour]', 'hour_of_day_perform_auto_operations', get_option('invoice_auto_operations_hour'), 'number', ['data-toggle' => 'tooltip', 'data-title' => _l('hour_of_day_perform_auto_operations_format'), 'max' => 23]); ?>
        <hr />
        <div class="row">
            <div class="col-md-12">
                <?php if (!is_invoices_overdue_reminders_enabled()) { ?>
                <div class="alert alert-warning">
                    The system was not able to find sources to send overdue notices, if you want overdue notices to
                    be
                    sent, make sure that in <a href="<?php echo admin_url('emails'); ?>">email templates</a> the
                    <b>Invoice Overdue Notice</b> template for invoices is enabled or at least you have configured
                    <a href="<?php echo admin_url('settings?group=sms'); ?>">SMS</a> overdue notice. If you don't
                    need
                    to send overdue notices for invoices, simply ignore this message.
                </div>
                <?php } ?>
                <h4 class="no-mbot font-medium"><?php echo _l('overdue_notices'); ?></h4>
                <p><?php echo _l('invoice_overdue_notices_info'); ?></p>
            </div>
            <div class="col-md-6">
                <?php echo render_input('settings[automatically_send_invoice_overdue_reminder_after]', 'automatically_send_invoice_overdue_reminder_after', get_option('automatically_send_invoice_overdue_reminder_after'), 'number'); ?>
            </div>
            <div class="col-md-6">
                <?php echo render_input('settings[automatically_resend_invoice_overdue_reminder_after]', 'automatically_resend_invoice_overdue_reminder_after', get_option('automatically_resend_invoice_overdue_reminder_after'), 'number'); ?>
            </div>
            <div class="col-md-12">
                <?php if (!is_invoices_due_reminders_enabled()) { ?>
                <div class="alert alert-warning">
                    The system was not able to find sources to send invoices becoming due notices, if you want due
                    notices to be sent, make sure that in <a href="<?php echo admin_url('emails'); ?>">email
                        templates</a> the <b>Invoice Due Notice</b> template for invoices is enabled or at least you
                    have configured <a href="<?php echo admin_url('settings?group=sms'); ?>">SMS</a> due notice. If
                    you
                    don't need to send due notices for invoices, simply ignore this message.
                </div>
                <?php } ?>
                <h4 class="no-mbot font-medium"><?php echo _l('due_reminders'); ?></h4>
                <p><?php echo _l('due_reminders_for_invoices_info'); ?></p>
            </div>
            <div class="col-md-6">
                <?php echo render_input('settings[invoice_due_notice_before]', 'invoice_due_notice_before', get_option('invoice_due_notice_before'), 'number'); ?>
            </div>
            <div class="col-md-6">
                <?php echo render_input('settings[invoice_due_notice_resend_after]', 'automatically_resend_invoice_overdue_reminder_after', get_option('invoice_due_notice_resend_after'), 'number'); ?>
            </div>
        </div>
        <hr />
        <h4 class="mbot20 font-medium"><?php echo _l('invoices_list_recurring'); ?></h4>
        <div class="radio radio-info">
            <input type="radio" id="generate_and_send" name="settings[new_recurring_invoice_action]"
                value="generate_and_send" <?php if (get_option('new_recurring_invoice_action') == 'generate_and_send') {
    echo ' checked';
} ?>>
            <label for="generate_and_send"><?php echo _l('reccuring_invoice_option_gen_and_send'); ?></label>
        </div>
        <div class="radio radio-info">
            <input type="radio" id="generate_unpaid_invoice" name="settings[new_recurring_invoice_action]"
                value="generate_unpaid" <?php if (get_option('new_recurring_invoice_action') == 'generate_unpaid') {
    echo ' checked';
} ?>>
            <label for="generate_unpaid_invoice"><?php echo _l('reccuring_invoice_option_gen_unpaid'); ?></label>
        </div>
        <div class="radio radio-info">
            <input type="radio" id="generate_draft_invoice" name="settings[new_recurring_invoice_action]"
                value="generate_draft" <?php if (get_option('new_recurring_invoice_action') == 'generate_draft') {
    echo ' checked';
} ?>>
            <label for="generate_draft_invoice"><?php echo _l('reccuring_invoice_option_gen_draft'); ?></label>
        </div>
        <hr />
        <?php render_yes_no_option('create_invoice_from_recurring_only_on_paid_invoices', 'invoices_create_invoice_from_recurring_only_on_paid_invoices', 'invoices_create_invoice_from_recurring_only_on_paid_invoices_tooltip'); ?>

    </div>
    <div role="tabpanel" class="tab-pane" id="tasks">
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('hour_of_day_perform_tasks_reminder_notification_help'); ?>"></i>
        <?php echo render_input('settings[tasks_reminder_notification_hour]', 'hour_of_day_perform_auto_operations', get_option('tasks_reminder_notification_hour'), 'number', ['data-toggle' => 'tooltip', 'data-title' => _l('hour_of_day_perform_auto_operations_format'), 'max' => 23]); ?>
        <hr />
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('tasks_reminder_notification_before_help'); ?>"></i>
        <?php echo render_input('settings[tasks_reminder_notification_before]', 'tasks_reminder_notification_before', get_option('tasks_reminder_notification_before'), 'number'); ?>

        <?php echo render_input('settings[automatically_stop_task_timer_after_hours]', 'automatically_stop_task_timer_after_hours', get_option('automatically_stop_task_timer_after_hours'), 'number'); ?>
        <hr />
        <?php
      render_yes_no_option('reminder_for_completed_but_not_billed_tasks', 'send_reminder_for_completed_but_not_billed_tasks');
      ?>
        <div
            class="staff_notify_completed_but_not_billed_tasks_fields <?php echo get_option('reminder_for_completed_but_not_billed_tasks') == '1' ? '' : 'hide'; ?>">
            <?php
        $selected = get_staff_user_id();
        if (!empty(get_option('staff_notify_completed_but_not_billed_tasks'))) {
            $selected = json_decode(get_option('staff_notify_completed_but_not_billed_tasks'));
        }
        echo render_select('settings[staff_notify_completed_but_not_billed_tasks][]', $staff, ['staffid', ['firstname', 'lastname']], 'staff_to_notify_completed_but_not_billed_tasks', $selected, ['multiple' => true], [], '', '', false);

        $weekdays = [];
        foreach (array_combine(get_weekdays_original(), get_weekdays()) as $key => $day) {
            $weekdays[] = ['id' => $key, 'day' => $day];
        }
        $selected = json_decode(get_option('reminder_for_completed_but_not_billed_tasks_days'));
        if (empty($selected)) {
            $selected = ['Monday'];
        }
        echo render_select('settings[reminder_for_completed_but_not_billed_tasks_days][]', $weekdays, ['id', ['day']], 'reminder_for_completed_but_not_billed_tasks_days', $selected, ['multiple' => true, 'data'], [], '', '', false); ?>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="contracts">
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('hour_of_day_perform_auto_operations_format'); ?>"></i>
        <?php echo render_input('settings[contracts_auto_operations_hour]', 'hour_of_day_perform_auto_operations', get_option('contracts_auto_operations_hour'), 'number', ['max' => 23]); ?>

        <hr />
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('settings_reminders_contracts_tooltip'); ?>"></i>
        <?php echo render_input('settings[contract_expiration_before]', 'send_expiry_reminder_before', get_option('contract_expiration_before'), 'number'); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="tickets">
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('auto_close_tickets_disable'); ?>"></i>
        <?php echo render_input('settings[autoclose_tickets_after]', 'auto_close_ticket_after', get_option('autoclose_tickets_after'), 'number'); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="estimates">
        <?php if (!is_estimates_expiry_reminders_enabled()) { ?>
        <div class="alert alert-warning">
            The system was not able to find sources to send expiry reminders, if you want expiry reminders to be
            sent,
            make sure that in <a href="<?php echo admin_url('emails'); ?>">email templates</a> the expiry reminder
            email
            for estimates is enabled or at least you have configured <a
                href="<?php echo admin_url('settings?group=sms'); ?>">SMS</a> expiry reminder.
        </div>
        <?php } ?>
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('hour_of_day_perform_auto_operations_format'); ?>"></i>
        <?php echo render_input('settings[estimates_auto_operations_hour]', 'hour_of_day_perform_auto_operations', get_option('estimates_auto_operations_hour'), 'number', ['max' => 23]); ?>
        <hr />
        <?php echo render_input('settings[send_estimate_expiry_reminder_before]', 'send_expiry_reminder_before', get_option('send_estimate_expiry_reminder_before'), 'number'); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="proposals">
        <?php if (!is_proposals_expiry_reminders_enabled()) { ?>
        <div class="alert alert-warning">
            The system was not able to find sources to send expiry reminders, if you want expiry reminders to be
            sent,
            make sure that in <a href="<?php echo admin_url('emails'); ?>">email templates</a> the expiry reminder
            email
            for proposals is enabled or at least you have configured <a
                href="<?php echo admin_url('settings?group=sms'); ?>">SMS</a> expiry reminder.
        </div>
        <?php } ?>
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('hour_of_day_perform_auto_operations_format'); ?>"></i>
        <?php echo render_input('settings[proposals_auto_operations_hour]', 'hour_of_day_perform_auto_operations', get_option('proposals_auto_operations_hour'), 'number', ['max' => 23]); ?>
        <hr />

        <?php echo render_input('settings[send_proposal_expiry_reminder_before]', 'send_expiry_reminder_before', get_option('send_proposal_expiry_reminder_before'), 'number'); ?>
    </div>

    <div role="tablpanel" class="tab-pane" id="expenses">
        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            data-title="<?php echo _l('hour_of_day_perform_auto_operations_format'); ?>"></i>
        <?php echo render_input('settings[expenses_auto_operations_hour]', 'hour_of_day_perform_auto_operations', get_option('expenses_auto_operations_hour'), 'number', ['max' => 23]); ?>
    </div>

    <?php hooks()->do_action('after_cron_settings_last_tab_content'); ?>
</div>
