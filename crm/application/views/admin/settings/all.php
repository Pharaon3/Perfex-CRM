<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open_multipart(
    (!isset($tab['update_url'])
    ? $this->uri->uri_string() . '?group=' . $tab['slug'] . ($this->input->get('tab') ? '&active_tab=' . $this->input->get('tab') : '')
    : $tab['update_url']),
    ['id' => 'settings-form', 'class' => isset($tab['update_url']) ? 'custom-update-url' : '']
);
?>
        <div class="row">
            <?php if ($this->session->flashdata('debug')) {
    ?>
            <div class="col-lg-12">
                <div class="alert alert-warning">
                    <?php echo $this->session->flashdata('debug'); ?>
                </div>
            </div>
            <?php
} ?>
            <div class="col-md-3">
                <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
                    <?php echo _l('settings'); ?>
                </h4>
                <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
                    <?php
$i = 0;
foreach ($tabs as $group) { ?>
                    <li class="settings-group-<?php echo $group['slug']; ?><?php echo ($i === 0) ? ' active' : '' ?>">
                        <a href="<?php echo admin_url('settings?group=' . $group['slug']); ?>"
                            data-group="<?php echo $group['slug']; ?>">
                            <i class="<?php echo $group['icon'] ?: 'fa-regular fa-circle-question'; ?> menu-icon"></i>
                            <?php echo $group['name']; ?>

                            <?php if (isset($group['badge'], $group['badge']['value']) && !empty($group['badge'])) {?>
                            <span
                                class="badge pull-right
        <?=isset($group['badge']['type']) && $group['badge']['type'] != '' ? "bg-{$group['badge']['type']}" : 'bg-info' ?>" <?=(isset($group['badge']['type']) && $group['badge']['type'] == '') ||
        isset($group['badge']['color']) ? "style='background-color: {$group['badge']['color']}'" : '' ?>>
                                <?= $group['badge']['value'] ?>
                            </span>
                            <?php } ?>

                        </a>
                    </li>
                    <?php $i++;
    }
    ?>
                </ul>

                <a href="<?php echo admin_url('settings?group=update'); ?>"
                    class="tw-flex tw-items-center tw-mb-2 tw-ml-3 settings-group-system-update<?php echo $this->input->get('group') == 'update' ? 'bold': ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    <?php echo _l('settings_update'); ?>
                </a>
                <?php if (is_admin()) {
        ?>
                <a href="<?php echo admin_url('settings?group=info'); ?>"
                    class="tw-flex tw-items-center tw-ml-3 settings-group-system-info<?php echo $this->input->get('group') == 'info' ? 'bold' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    System/Server Info
                </a>
                <?php
    } ?>

                <div class="btn-bottom-toolbar text-right">
                    <button type="submit" class="btn btn-primary">
                        <?php echo _l('settings_save'); ?>
                    </button>
                </div>
            </div>
            <div class="col-md-9">
                <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
                    <?php echo _l($tab['name']); ?>
                </h4>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php hooks()->do_action('before_settings_group_view', $tab); ?>
                        <?php $this->load->view($tab['view']) ?>
                        <?php hooks()->do_action('after_settings_group_view', $tab); ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php echo form_close(); ?>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>
<script>
$(function() {
    var slug = "<?php echo $tab['slug']; ?>";
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var settingsForm = $('#settings-form');

        if (settingsForm.hasClass('custom-update-url')) {
            return;
        }

        var tab = $(this).attr('href').slice(1);
        settingsForm.attr('action', '<?php echo site_url($this->uri->uri_string()); ?>?group=' + slug +
            '&active_tab=' + tab);
    });

    $('input[name="settings[email_protocol]"]').on('change', function() {
        if ($(this).val() == 'mail') {
            $('.smtp-fields').addClass('hide');
        } else {
            $('.smtp-fields').removeClass('hide');
        }
    });

    $('.sms_gateway_active input').on('change', function() {
        if ($(this).val() == '1') {
            $('body .sms_gateway_active').not($(this).parents('.sms_gateway_active')[0]).find(
                'input[value="0"]').prop('checked', true);
        }
    });

    <?php if ($tab['slug'] == 'pusher') {
        if (get_option('desktop_notifications') == '1') {
            ?>
    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
        $('#pusherHelper').html(
            '<div class="alert alert-danger">Your browser does not support desktop notifications, please disable this option or use more modern browser.</div>'
        );
    } else if (Notification.permission == "denied") {
        $('#pusherHelper').html(
            '<div class="alert alert-danger">Desktop notifications not allowed in browser settings, search on Google "How to allow desktop notifications for <?php echo $this->agent->browser(); ?>"</div>'
        );
    }
    <?php
        } ?>
    <?php if (get_option('pusher_realtime_notifications') == '0') {
            ?>
    $('input[name="settings[desktop_notifications]"]').prop('disabled', true);
    <?php
        } ?>
    <?php
    } ?>

    $('input[name="settings[pusher_realtime_notifications]"]').on('change', function() {
        if ($(this).val() == '1') {
            $('input[name="settings[desktop_notifications]"]').prop('disabled', false);
        } else {
            $('input[name="settings[desktop_notifications]"]').prop('disabled', true);
            $('input[name="settings[desktop_notifications]"][value="0"]').prop('checked', true);
        }
    });

    $('.test_email').on('click', function() {
        var email = $('input[name="test_email"]').val();
        if (email != '') {
            $(this).attr('disabled', true);
            $.post(admin_url + 'emails/sent_smtp_test_email', {
                test_email: email
            }).done(function(data) {
                window.location.reload();
            });
        }
    });

    $('#update_app').on('click', function(e) {
        e.preventDefault();
        $('input[name="settings[purchase_key]"]').parents('.form-group').removeClass('has-error');
        var purchase_key = $('input[name="settings[purchase_key]"]').val();
        var latest_version = $('input[name="latest_version"]').val();
        var upgrade_function = $('input[name="upgrade_function"]:checked').val();
        var update_errors;
        if (purchase_key != '') {
            var ubtn = $(this);
            ubtn.html('<?php echo _l('wait_text'); ?>');
            ubtn.addClass('disabled');
            $.post(admin_url + 'auto_update', {
                purchase_key: purchase_key,
                latest_version: latest_version,
                auto_update: true,
                upgrade_function: upgrade_function
            }).done(function() {
                window.location.reload();
            }).fail(function(response) {
                update_errors = JSON.parse(response.responseText);
                $('#update_messages').html('<div class="alert alert-danger"></div>');
                for (var i in update_errors) {
                    $('#update_messages .alert').append('<p>' + update_errors[i] + '</p>');
                }
                ubtn.removeClass('disabled');
                ubtn.html($('.update_app_wrapper').data('original-text'));
            });
        } else {
            $('input[name="settings[purchase_key]"]').parents('.form-group').addClass('has-error');
        }
    });
});

$('input[name="settings[reminder_for_completed_but_not_billed_tasks]"]').on('change', function() {
    if ($(this).val() == '1') {
        $('.staff_notify_completed_but_not_billed_tasks_fields').removeClass('hide');
    } else {
        $('.staff_notify_completed_but_not_billed_tasks_fields').addClass('hide');
    }
});
</script>
<?php hooks()->do_action('settings_group_end', $tab); ?>
</body>

</html>