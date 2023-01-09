<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (is_client_logged_in() && $navigationEnabled) {
    $_announcements = get_announcements_for_user(false);
    if (count($_announcements) > 0) { ?>
<div class="panel_s">
    <?php foreach ($_announcements as $__announcement) { ?>
    <div class="panel-body announcement mbot15 tc-content">
        <div class="alert-dismissible" role="alert">
            <a href="<?php echo site_url('clients/dismiss_announcement/' . $__announcement['announcementid']); ?>"
                class="close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="tw-w-5 tw-h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
            <h4 class="tw-my-0 text-info tw-font-medium">
                <?php echo _l('announcement'); ?>
                <?php if ($__announcement['showname'] == 1) {
        echo _l('announcement_from') . ' ' . $__announcement['userid'];
    } ?>!
            </h4>
            <small>
                <?php echo _l('announcement_date', _dt($__announcement['dateadded'])); ?>
            </small>
        </div>
        <h4 class="announcement-name tw-mb-0 tw-mt-5 tw-font-semibold">
            <?php echo $__announcement['name']; ?>
        </h4>
        <div class="announcement-message tw-text-neutral-500 [&>p:last-child]:tw-mb-0">
            <?php echo check_for_links($__announcement['message']); ?>
        </div>
    </div>
    <?php } ?>
</div>
<?php } ?>
<?php
} ?>