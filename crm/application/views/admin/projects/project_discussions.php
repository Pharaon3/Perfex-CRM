<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (!isset($discussion)) { ?>
<a href="#" onclick="new_discussion();return false;" class="btn btn-primary mbot25">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?php echo _l('new_project_discussion'); ?>
</a>
<div class="panel_s panel-table-full">
    <div class="panel-body">
        <?php
    $this->load->view('admin/projects/project_discussion');
    render_datatable([
     _l('project_discussion_subject'),
     _l('project_discussion_last_activity'),
     _l('project_discussion_total_comments'),
     _l('project_discussion_show_to_customer'),
     ], 'project-discussions'); ?>
    </div>
</div>

<?php } else { ?>

<h3 class="tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mt-0"><?php echo $discussion->subject; ?></h3>

<div class="panel_s">
    <div class="panel-body">
        <p class="no-margin"><?php echo _l('project_discussion_posted_on', _d($discussion->datecreated)); ?></p>
        <p class="no-margin">
            <?php
        if ($discussion->staff_id == 0) {
            echo _l('project_discussion_posted_by', get_contact_full_name($discussion->contact_id)) . ' <span class="label label-info inline-block">' . _l('is_customer_indicator') . '</span>';
        } else {
            echo _l('project_discussion_posted_by', get_staff_full_name($discussion->staff_id));
        }
    ?>
        </p>
        <p><?php echo _l('project_discussion_total_comments'); ?>:
            <?php echo total_rows(db_prefix() . 'projectdiscussioncomments', ['discussion_id' => $discussion->id, 'discussion_type' => 'regular']); ?>
        </p>
        <p class="text-muted"><?php echo $discussion->description; ?></p>
        <hr />
        <div id="discussion-comments" class="tc-content"></div>
    </div>
</div>

<?php } ?>