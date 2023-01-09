<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-estimates">
    <?php echo _l('clients_my_estimates'); ?>
</h4>
<div class="panel_s">
    <div class="panel-body">
        <?php get_template_part('estimates_stats'); ?>
        <hr />
        <?php get_template_part('estimates_table'); ?>
    </div>
</div>