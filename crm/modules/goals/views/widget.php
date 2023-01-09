<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$goals = [];
if (is_staff_member()) {
    $this->load->model('goals/goals_model');
    $goals = $this->goals_model->get_all_goals();
}
?>
<div class="widget<?php if (count($goals) == 0 || !is_staff_member()) {
    echo ' hide';
} ?>" id="widget-<?php echo create_widget_id('goals'); ?>">
    <?php if (is_staff_member()) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body padding-10">
                    <div class="widget-dragger"></div>

                    <p
                        class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse tw-p-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>

                        <span class="tw-text-neutral-700">
                            <?php echo _l('goals'); ?>
                        </span>
                    </p>

                    <hr class="-tw-mx-3 tw-mt-3 tw-mb-6">

                    <?php foreach ($goals as $goal) {
    ?>
                    <div class="goal tw-px-1 tw-pb-1">
                        <h4 class="pull-left font-medium no-mtop">
                            <?php echo $goal['goal_type_name']; ?>
                            <br />
                            <small><?php echo $goal['subject']; ?></small>
                        </h4>
                        <h4 class="pull-right bold no-mtop text-success text-right">
                            <?php echo $goal['achievement']['total']; ?>
                            <br />
                            <small><?php echo _l('goal_achievement'); ?></small>
                        </h4>
                        <div class="clearfix"></div>
                        <div class="progress no-margin progress-bar-mini">
                            <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar"
                                aria-valuenow="<?php echo $goal['achievement']['percent']; ?>" aria-valuemin="0"
                                aria-valuemax="100" style="width: 0%"
                                data-percent="<?php echo $goal['achievement']['percent']; ?>">
                            </div>
                        </div>
                        <p class="text-muted pull-left mtop5"><?php echo _l('goal_progress'); ?></p>
                        <p class="text-muted pull-right mtop5"><?php echo $goal['achievement']['percent']; ?>%</p>
                    </div>
                    <?php
} ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>