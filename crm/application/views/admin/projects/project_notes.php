<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
    <?php echo _l('project_note_private'); ?>
</h4>
<?php echo form_open(admin_url('projects/save_note/' . $project->id)); ?>
<?php echo render_textarea('content', '', $staff_notes, [], [], '', 'tinymce'); ?>
<div class="text-right">
    <button type="submit" class="btn btn-primary"><?php echo _l('project_save_note'); ?></button>
</div>
<?php echo form_close(); ?>
