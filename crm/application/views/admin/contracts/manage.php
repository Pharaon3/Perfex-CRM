<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons">
                    <?php if (has_permission('contracts', '', 'create')) { ?>
                    <a href="<?php echo admin_url('contracts/contract'); ?>"
                        class="btn btn-primary pull-left display-block tw-mb-2 sm:tw-mb-4">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_contract'); ?>
                    </a>
                    <?php } ?>
                    <?php $this->load->view('admin/contracts/filters'); ?>
                    <div class="clearfix"></div>
                    <div id="contract_summary">
                        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>

                            <span>
                                <?php echo _l('contract_summary_heading'); ?>
                            </span>
                        </h4>
                        <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-5 tw-gap-2">
                            <div
                                class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold sm:tw-w-auto tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php echo $count_active; ?></span>
                                <span class="text-info"><?php echo _l('contract_summary_active'); ?></span>
                            </div>
                            <div
                                class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold sm:tw-w-auto tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php echo $count_expired; ?></span>
                                <span class="text-danger"><?php echo _l('contract_summary_expired'); ?></span>
                            </div>
                            <div
                                class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold sm:tw-w-auto tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php echo count($expiring); ?>
                                </span>
                                <span class="text-warning"><?php echo _l('contract_summary_about_to_expire'); ?></span>
                            </div>
                            <div
                                class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold sm:tw-w-auto tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php echo $count_recently_created; ?></span>
                                <span class="text-success"><?php echo _l('contract_summary_recently_added'); ?></span>
                            </div>
                            <div
                                class="tw-flex tw-items-center md:tw-border-r md:tw-border-solid tw-flex-1 md:tw-border-neutral-300 lg:tw-border-0">
                                <span class="tw-font-semibold sm:tw-w-auto tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php echo $count_trash; ?></span>
                                <span class="text-muted"><?php echo _l('contract_summary_trash'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel_s tw-mt-2 sm:tw-mt-4">
                    <?php echo form_hidden('custom_view'); ?>
                    <div class="panel-body">
                        <div class="row ">

                            <div class="col-md-6 border-right">
                                <h4 class="tw-font-semibold tw-mb-8"><?php echo _l('contract_summary_by_type'); ?></h4>
                                <div class="relative" style="max-height:400px">
                                    <canvas class="chart" height="400" id="contracts-by-type-chart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="tw-font-semibold tw-mb-8">
                                    <?php echo _l('contract_summary_by_type_value'); ?>
                                    (<span data-toggle="tooltip" data-title="<?php echo _l('base_currency_string'); ?>"
                                        class="text-has-action">
                                        <?php echo $base_currency->name; ?></span>)
                                </h4>
                                <div class="relative" style="max-height:400px">
                                    <canvas class="chart" height="400" id="contracts-value-by-type-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="panel-table-full tw-mt-10">
                            <?php $this->load->view('admin/contracts/table_html'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {

    var ContractsServerParams = {};
    $.each($('._hidden_inputs._filters input'), function() {
        ContractsServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
    });

    initDataTable('.table-contracts', admin_url + 'contracts/table', undefined, undefined,
        ContractsServerParams,
        <?php echo hooks()->apply_filters('contracts_table_default_order', json_encode([6, 'asc'])); ?>);

    new Chart($('#contracts-by-type-chart'), {
        type: 'bar',
        data: <?php echo $chart_types; ?>,
        options: {
            legend: {
                display: false,
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        suggestedMin: 0,
                    }
                }]
            }
        }
    });
    new Chart($('#contracts-value-by-type-chart'), {
        type: 'line',
        data: <?php echo $chart_types_values; ?>,
        options: {
            responsive: true,
            legend: {
                display: false,
            },
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        suggestedMin: 0,
                    }
                }]
            }
        }
    });
});
</script>
</body>

</html>