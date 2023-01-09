<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (is_admin()) { ?>
                <a href="<?php echo admin_url('announcements/announcement'); ?>"
                    class="btn btn-primary tw-mb-2 sm:tw-mb-4">
                    <i class="fa-regular fa-plus tw-mr-1"></i>
                    <?php echo _l('new_announcement'); ?>
                </a>
                <?php } else { ?>
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo _l('announcements'); ?>
                </h4>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([_l('name'), _l('announcement_date_list')], 'announcements'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php init_tail(); ?>
    </body>

    </html>