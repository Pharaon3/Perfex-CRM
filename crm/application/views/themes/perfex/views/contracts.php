<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h3 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 contracts-summary-heading">
    <?php echo _l('contract_summary_by_type'); ?>
</h3>

<div class="relative tw-mb-10" style="max-height:300px;">
    <canvas class="chart" height="300" id="contracts-by-type-chart"></canvas>
</div>

<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-contracts">
    <?php echo _l('clients_contracts'); ?>
</h4>

<div class="panel_s">
    <div class="panel-body">
        <?php get_template_part('contracts_table'); ?>
    </div>
</div>
<script>
var contracts_by_type = '<?php echo $contracts_by_type_chart; ?>';
</script>