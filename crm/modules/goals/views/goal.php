<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-<?php echo !isset($goal) ? '8 col-md-offset-2' : 6; ?>">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><?php echo $title; ?></h4>
                <?php echo form_open($this->uri->uri_string()); ?>
                <div class="panel_s">
                    <div class="panel-body">

                        <?php $attrs = (isset($goal) ? [] : ['autofocus' => true]); ?>
                        <?php $value = (isset($goal) ? $goal->subject : ''); ?>
                        <?php echo render_input('subject', 'goal_subject', $value, 'text', $attrs); ?>
                        <div class="form-group select-placeholder">
                            <label for="goal_type" class="control-label"><?php echo _l('goal_type'); ?></label>
                            <select name="goal_type" class="selectpicker" data-width="100%"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <?php foreach (get_goal_types() as $type) { ?>
                                <option value="<?php echo $type['key']; ?>" data-subtext="<?php if (isset($type['subtext'])) {
    echo _l($type['subtext']);
} ?>" <?php if (isset($goal) && $goal->goal_type == $type['key']) {
    echo 'selected';
} ?>>
                                    <?php echo _l($type['lang_key']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                           $selected = (isset($goal) ? $goal->staff_id : '');
                           echo render_select('staff_id', $members, ['staffid', ['firstname', 'lastname']], 'staff_member', $selected, ['data-none-selected-text' => _l('all_staff_members')]); ?>
                        <?php $value = (isset($goal) ? $goal->achievement : ''); ?>
                        <?php echo render_input('achievement', 'goal_achievement', $value, 'number'); ?>
                        <?php $value = (isset($goal) ? _d($goal->start_date) : _d(date('Y-m-d'))); ?>
                        <?php echo render_date_input('start_date', 'goal_start_date', $value); ?>
                        <?php $value = (isset($goal) ? _d($goal->end_date) : ''); ?>
                        <?php echo render_date_input('end_date', 'goal_end_date', $value); ?>
                        <div class="hide" id="contract_types">
                            <?php $selected = (isset($goal) ? $goal->contract_type : ''); ?>
                            <?php echo render_select('contract_type', $contract_types, ['id', 'name'], 'goal_contract_type', $selected); ?>
                        </div>
                        <?php $value = (isset($goal) ? $goal->description : ''); ?>
                        <?php echo render_textarea('description', 'goal_description', $value); ?>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="notify_when_achieve" id="notify_when_achieve" <?php if (isset($goal)) {
                               if ($goal->notify_when_achieve == 1) {
                                   echo 'checked';
                               }
                           } else {
                               echo 'checked';
                           } ?>>
                            <label for="notify_when_achieve"><?php echo _l('goal_notify_when_achieve'); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="notify_when_fail" id="notify_when_fail" <?php if (isset($goal)) {
                               if ($goal->notify_when_fail == 1) {
                                   echo 'checked';
                               }
                           } else {
                               echo 'checked';
                           } ?>>
                            <label for="notify_when_fail"><?php echo _l('goal_notify_when_fail'); ?></label>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary "><?php echo _l('submit'); ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php if (isset($goal)) { ?>
            <?php
                        $show_acchievement_label = false;
                        $help_text               = '';
                        if ($goal->end_date < date('Y-m-d')) {
                            $achieve_indicator_class = 'danger';
                            $lang_key                = 'goal_failed';
                            $finished                = true;
                            $notify_type             = 'failed';

                            if ($goal->notified == 1) {
                                $help_text = '<p class="text-muted text-center">' . _l('goal_staff_members_notified_about_failure') . '</p>';
                            }

                            $show_acchievement_label = true;
                        } elseif ($achievement['percent'] == 100) {
                            $achieve_indicator_class = 'success';
                            $show_acchievement_label = true;
                            if ($goal->notified == 1) {
                                $help_text = '<p class="text-muted text-center">' . _l('goal_staff_members_notified_about_achievement') . '</p>';
                            }

                            $notify_type = 'success';
                            $finished    = true;
                            $lang_key    = 'goal_achieved';
                        } elseif ($achievement['percent'] >= 80) {
                            $achieve_indicator_class = 'warning';
                            $show_acchievement_label = true;
                            $lang_key                = 'goal_close';
                        }


              ?>
            <div class="col-md-6">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo _l('goal_achievement'); ?>
                    <?php
                    if ($show_acchievement_label == true) {
                        echo '<span class="tw-ml-1.5 label label-' . $achieve_indicator_class . '">' . _l($lang_key) . '</span>';
                    }
                    ?>
                </h4>
                <div class="panel_s">
                    <div class="panel-body">

                        <h3 class="text-center tw-font-semibold"><?php echo _l('goal_result_heading'); ?>
                            <small class="tw-font-medium"><?php echo _l('goal_total', $achievement['total']); ?></small>
                        </h3>
                        <?php if ($goal->goal_type == 1) {
                        echo '<p class="text-center no-mbot">' . _l('goal_income_shown_in_base_currency') . '</p>';
                    }
            if ((isset($finished) && $goal->notified == 0) && ($goal->notify_when_achieve == 1 || $goal->notify_when_fail == 1)) {
                echo '<p class="text-center text-info">' . _l('goal_notify_when_end_date_arrives') . '</p>';

                echo '<div class="text-center"><a href="' . admin_url('goals/notify/' . $goal->id . '/' . $notify_type) . '" class="btn btn-default">' . _l('goal_notify_staff_manually') . '</a></div>';
            }
            echo $help_text;
            ?>
                        <div class="achievement mtop30" data-toggle="tooltip"
                            title="<?php echo _l('goal_total', $achievement['total']); ?>">
                            <div class="goal-progress" data-thickness="40" data-reverse="true">
                                <strong class="goal-percent"></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    appValidateForm($('form'), {
        subject: 'required',
        goal_type: 'required',
        end_date: 'required',
        start_date: 'required',
        contract_type: {
            required: {
                depends: function(element) {
                    return $('select[name="goal_type"]').val() == 5 || $('select[name="goal_type"]')
                        .val() == 7;
                }
            }
        }
    });
    <?php if (isset($goal)) { ?>
    var circle = $('.goal-progress').circleProgress({
        value: '<?php echo $achievement['progress_bar_percent']; ?>',
        size: 250,
        fill: {
            gradient: ["#28b8da", "#059DC1"]
        }
    }).on('circle-animation-progress', function(event, progress, stepValue) {
        $(this).find('strong.goal-percent').html(parseInt(100 * stepValue) + '<i>%</i>');
    });
    <?php } ?>
    var goal_type = $('select[name="goal_type"]').val();
    if (goal_type == 5 || goal_type == 7) {
        $('#contract_types').removeClass('hide');
    }
    $('select[name="goal_type"]').on('change', function() {
        var goal_type = $(this).val();
        if (goal_type == 5 || goal_type == 7) {
            $('#contract_types').removeClass('hide');
        } else {
            $('#contract_types').addClass('hide');
            $('#contract_type').selectpicker('val', '');
        }
    });
});
</script>
</body>

</html>