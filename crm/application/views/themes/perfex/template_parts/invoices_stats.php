<?php defined('BASEPATH') or exit('No direct script access allowed');

$where_total = 'clientid=' . get_client_user_id() . ' AND status !=5';
if (get_option('exclude_invoice_from_client_area_with_draft_status') == 1) {
    $where_total .= ' AND status != 6';
}

$total_invoices            = total_rows(db_prefix() . 'invoices', $where_total);
$total_open                = total_rows(db_prefix() . 'invoices', ['status' => 1, 'clientid' => get_client_user_id()]);
$total_paid                = total_rows(db_prefix() . 'invoices', ['status' => 2, 'clientid' => get_client_user_id()]);
$total_not_paid_completely = total_rows(db_prefix() . 'invoices', ['status' => 3, 'clientid' => get_client_user_id()]);
$total_overdue             = total_rows(db_prefix() . 'invoices', ['status' => 4, 'clientid' => get_client_user_id()]);

$percent_open                = ($total_invoices > 0 ? number_format(($total_open * 100) / $total_invoices, 2) : 0);
$percent_paid                = ($total_invoices > 0 ? number_format(($total_paid * 100) / $total_invoices, 2) : 0);
$percent_overdue             = ($total_invoices > 0 ? number_format(($total_overdue * 100) / $total_invoices, 2) : 0);
$percent_not_paid_completely = ($total_invoices > 0 ? number_format(($total_not_paid_completely * 100) / $total_invoices, 2) : 0);

?>
<div class="row text-left invoice-quick-info invoices-stats">
    <div class="col-md-3 invoices-stats-unpaid">
        <div class="row">
            <div class="col-md-8 stats-status">
                <a href="<?php echo site_url('clients/invoices/1'); ?>"
                    class="tw-text-neutral-600 hover:tw-text-neutral-800 active:tw-text-neutral-800 tw-font-medium">
                    <?php echo _l('invoice_status_unpaid'); ?>
                </a>
            </div>
            <div class="col-md-4 text-right tw-font-medium stats-numbers">
                <?php echo $total_open; ?> / <?php echo $total_invoices; ?>
            </div>
            <div class="col-md-12 tw-mt-1.5">
                <div class="progress">
                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                        data-percent="<?php echo $percent_open; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 invoices-stats-paid">
        <div class="row">
            <div class="col-md-8 stats-status">
                <a href="<?php echo site_url('clients/invoices/2'); ?>"
                    class="tw-text-neutral-600 hover:tw-text-neutral-800 active:tw-text-neutral-800 tw-font-medium">
                    <?php echo _l('invoice_status_paid'); ?>
                </a>
            </div>
            <div class="col-md-4 text-right stats-numbers bold">
                <?php echo $total_paid; ?> / <?php echo $total_invoices; ?>
            </div>
            <div class="col-md-12 tw-mt-1.5">
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                        data-percent="<?php echo $percent_paid; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 invoices-stats-overdue">
        <div class="row">
            <div class="col-md-8 stats-status">
                <a href="<?php echo site_url('clients/invoices/4'); ?>"
                    class="tw-text-neutral-600 hover:tw-text-neutral-800 active:tw-text-neutral-800 tw-font-medium">
                    <?php echo _l('invoice_status_overdue'); ?>
                </a>
            </div>
            <div class="col-md-4 text-right stats-numbers bold">
                <?php echo $total_overdue; ?> / <?php echo $total_invoices; ?>
            </div>
            <div class="col-md-12 tw-mt-1.5">
                <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                        data-percent="<?php echo $percent_overdue; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 invoices-stats-partially-paid">
        <div class="row">
            <div class="col-md-8 stats-status">
                <a href="<?php echo site_url('clients/invoices/3'); ?>"
                    class="tw-text-neutral-600 hover:tw-text-neutral-800 active:tw-text-neutral-800 tw-font-medium">
                    <?php echo _l('invoice_status_not_paid_completely'); ?>
                </a>
            </div>
            <div class="col-md-4 text-right stats-numbers bold">
                <?php echo $total_not_paid_completely; ?> / <?php echo $total_invoices; ?>
            </div>
            <div class="col-md-12 tw-mt-1.5">
                <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40"
                        aria-valuemin="0" aria-valuemax="100" style="width: 0%"
                        data-percent="<?php echo $percent_not_paid_completely; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>