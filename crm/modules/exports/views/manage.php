<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @var string $title
 * @var array[] $features
 */
init_head();
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><?php echo $title; ?></h4>
                <?php echo form_open($this->uri->uri_string(), ['id' => 'export-form']); ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group select-placeholder">
                                    <label for="export_type"><?php echo _l('csv_export_select_type'); ?></label>
                                    <select name="export_type" id="export_type" class="selectpicker" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""></option>
                                        <?php foreach ($features as $feature) { ?>
                                        <option value="<?php echo $feature['feature']; ?>">
                                            <?php echo $feature['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group select-placeholder">
                                    <label for="period"><?php echo _l('period_datepicker'); ?></label>
                                    <br />
                                    <select class="selectpicker" name="period" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value="all_time"><?php echo _l('csv_export_all_time'); ?></option>
                                        <option value="this_month"><?php echo _l('this_month'); ?></option>
                                        <option value="last_month"><?php echo _l('last_month'); ?></option>
                                        <option value="this_year"><?php echo _l('this_year'); ?></option>
                                        <option value="last_year"><?php echo _l('last_year'); ?></option>
                                        <option value="last_3_months" data-subtext="<?php echo _d(date(
    'Y-m-01',
    strtotime('-2 MONTH')
)); ?> - <?php echo _d(date('Y-m-t')); ?>">
                                            <?php echo _l('csv_export_three_months'); ?>
                                        </option>
                                        <option value="last_6_months" data-subtext="<?php echo _d(date(
    'Y-m-01',
    strtotime('-5 MONTH')
)); ?> - <?php echo _d(date('Y-m-t')); ?>">
                                            <?php echo _l('csv_export_six_months'); ?>
                                        </option>
                                        <option value="last_12_months" data-subtext="<?php echo _d(date(
    'Y-m-01',
    strtotime('-11 MONTH')
)); ?> - <?php echo _d(date('Y-m-t')); ?>">
                                            <?php echo _l('csv_export_twelve_months'); ?>
                                        </option>
                                        <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                                    </select>
                                </div>
                                <div id="date-range" class="hide mbot15">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="start_date"
                                                class="control-label"><?php echo _l('csv_export_from_date'); ?></label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control datepicker" id="start_date"
                                                    name="start_date" autocomplete="off">
                                                <div class="input-group-addon">
                                                    <i class="fa-regular fa-calendar calendar-icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="stop_date"
                                                class="control-label"><?php echo _l('csv_export_to_date'); ?></label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control datepicker" id="stop_date"
                                                    name="stop_date" autocomplete="off">
                                                <div class="input-group-addon">
                                                    <i class="fa-regular fa-calendar calendar-icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-primary" type="submit">
                            <?php echo _l('csv_export_button'); ?>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php init_tail(); ?>
<script>
var date_validation_rule = {
    required: {
        depends: function() {
            return $('select[name="period"]').val() === 'custom';
        }
    }
}
appValidateForm('#export-form', {
    'period': 'required',
    'export_type': 'required',
    'start_date': date_validation_rule,
    'stop_date': date_validation_rule,
});

var date_range = $('#date-range');
var start_date = $('input[name="start_date"]');
var stop_date = $('input[name="stop_date"]');

$('select[name="period"]').on('change', function() {
    var val = $(this).val();
    if (val == 'custom') {
        start_date.val('');
        stop_date.val('');
        date_range.addClass('fadeIn').removeClass('hide');
        return;
    } else {
        if (!date_range.hasClass('hide')) {
            date_range.removeClass('fadeIn').addClass('hide');
        }
    }
})
</script>
</body>

</html>