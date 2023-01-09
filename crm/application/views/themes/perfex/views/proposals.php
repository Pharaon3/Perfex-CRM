<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-proposals">
    <?php echo _l('proposals'); ?>
</h4>
<div class="panel_s">
    <div class="panel-body">
        <?php get_template_part('proposals_table'); ?>
    </div>
</div>