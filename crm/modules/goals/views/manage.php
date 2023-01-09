<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('goals', '', 'create')) { ?>
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('goals/goal'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_goal'); ?>
                    </a>
                </div>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([
                        _l('goal_subject'),
                        _l('staff_member'),
                        _l('goal_achievement'),
                        _l('goal_start_date'),
                        _l('goal_end_date'),
                        _l('goal_type'),
                        _l('goal_progress'),
                        ], 'goals'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-goals', window.location.href, [6], [6]);
    $('.table-goals').DataTable().on('draw', function() {
        var rows = $('.table-goals').find('tr');
        $.each(rows, function() {
            var td = $(this).find('td').eq(6);
            var percent = $(td).find('input[name="percent"]').val();
            $(td).find('.goal-progress').circleProgress({
                value: percent,
                size: 45,
                animation: false,
                fill: {
                    gradient: ["#28b8da", "#059DC1"]
                }
            })
        })
    })
});
</script>
</body>

</html>