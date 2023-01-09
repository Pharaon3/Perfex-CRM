<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Stripe Credit Cards UPDATE
 */
?>

<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-text section-heading-credit-card">
    <?php echo _l('update_credit_card'); ?>
</h4>

<div class="panel_s">
    <div class="panel-body credit-card">
        <?php if (!empty($payment_method)) { ?>
        <h4><?php echo _l('credit_card_update_info'); ?></h4>

        <a href="<?php echo site_url('clients/update_credit_card'); ?>" class="btn btn-primary">
            <?php echo _l('update_card_btn'); ?> (<?php echo $payment_method->card->brand; ?>
            <?php echo $payment_method->card->last4; ?>
        </a>

        <div<?php if (!customer_can_delete_credit_card()) { ?> data-toggle="tooltip"
            title="<?php echo _l('delete_credit_card_info'); ?>" <?php } ?> class="inline-block">
            <a class="btn btn-danger<?php if (!customer_can_delete_credit_card()) { ?> disabled<?php } ?>"
                href="<?php echo site_url('clients/delete_credit_card'); ?>">
                <?php echo _l('delete_credit_card'); ?>
            </a>
    </div>
    <?php } else { ?>
    <?php echo _l('no_credit_card_found'); ?>
    <?php } ?>
</div>
</div>
