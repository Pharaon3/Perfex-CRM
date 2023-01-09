<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<a href="#"
    class="tw-flex tw-items-center tw-justify-center tw-absolute tw-right-10 tw-h-8 tw-w-8 tw-bg-neutral-200 tw-rounded-full hover:tw-bg-neutral-300 close_newsfeed tw-transition-all tw-z-10 tw-top-9"
    data-close="true">
    <i class="fa fa-remove fa-lg tw-text-neutral-800"></i>
</a>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel_s">
            <div class="panel-body">
                <?php echo form_open_multipart('admin/newsfeed/add_post', ['class' => 'dropzone', 'id' => 'new-post-form']); ?>
                <a href="<?php echo admin_url('profile'); ?>">
                    <?php echo staff_profile_image($current_user->staffid, ['staff-profile-image-small']); ?>
                    <?php echo $current_user->firstname . ' ' . $current_user->lastname ; ?></a>
                <textarea name="content" id="post" rows="5" class="form-control"
                    placeholder="<?php echo _l('whats_on_your_mind'); ?>"></textarea>
                <hr />
                <button type="submit" class="btn btn-primary pull-right"><?php echo _l('new_post'); ?></button>
                <a href="#" class="btn btn-default add-post-attachments"><i data-toggle="tooltip"
                        title="<?php echo _l('newsfeed_upload_tooltip'); ?>" class="fa-solid fa-file"></i></a>
                <select id="post-visibility" class="selectpicker" multiple name="visibility[]" data-width="60%"
                    data-none-selected-text="<?php echo _l('newsfeed_all_departments'); ?>">

                    <?php foreach ($departments as $department) { ?>
                    <option value="<?php echo $department['departmentid']; ?>"><?php echo $department['name']; ?>
                    </option>
                    <?php } ?>
                </select>
                <div class="dz-message" data-dz-message><span></span></div>
                <div class="dropzone-previews mtop25"></div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <?php echo form_hidden('total_pages_newsfeed', hooks()->apply_filters('total_pages_newsfeed', total_rows(db_prefix() . 'newsfeed_posts') / 10)); ?>
        <div id="newsfeed_data"></div>
    </div>
</div>