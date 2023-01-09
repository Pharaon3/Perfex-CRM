<div role="tabpanel" class="tab-pane" id="surveys">
 <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip" data-title="<?php echo _l('settings_survey_send_emails_per_cron_run_tooltip'); ?>"></i>
  <?php echo render_input('settings[survey_send_emails_per_cron_run]', 'settings_survey_send_emails_per_cron_run', get_option('survey_send_emails_per_cron_run'), 'number'); ?>
</div>
