<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open($this->uri->uri_string(), ['id' => 'article-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                        <?php if (isset($article)) { ?>
                        <br />
                        <small>
                            <?php if ($article->staff_article == 1) { ?>
                            <a href="<?php echo admin_url('knowledge_base/view/' . $article->slug); ?>"
                                target="_blank"><?php echo admin_url('knowledge_base/view/' . $article->slug); ?></a>
                            <?php } else { ?>
                            <a href="<?php echo site_url('knowledge-base/article/' . $article->slug); ?>"
                                target="_blank"><?php echo site_url('knowledge-base/article/' . $article->slug); ?></a>
                            <?php } ?>
                        </small>
                        <br />
                        <small>
                            <?php echo _l('article_total_views'); ?>:
                            <?php echo total_rows(db_prefix() . 'views_tracking', ['rel_type' => 'kb_article', 'rel_id' => $article->articleid]);
                            ?>
                        </small>
                        <?php } ?>
                    </h4>
                    <div>
                        <?php if (isset($article)) { ?>
                        <p>

                            <?php if (has_permission('knowledge_base', '', 'create')) { ?>
                            <a href="<?php echo admin_url('knowledge_base/article'); ?>"
                                class="btn btn-success pull-right"><?php echo _l('kb_article_new_article'); ?></a>
                            <?php } ?>
                            <?php if (has_permission('knowledge_base', '', 'delete')) { ?>
                            <a href="<?php echo admin_url('knowledge_base/delete_article/' . $article->articleid); ?>"
                                class="btn btn-danger _delete pull-right mright5"><?php echo _l('delete'); ?></a>
                            <?php } ?>
                        <div class="clearfix"></div>
                        </p>
                        <?php } ?>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <?php $value = (isset($article) ? $article->subject : ''); ?>
                        <?php $attrs = (isset($article) ? [] : ['autofocus' => true]); ?>
                        <?php echo render_input('subject', 'kb_article_add_edit_subject', $value, 'text', $attrs); ?>
                        <?php if (isset($article)) {
                                echo render_input('slug', 'kb_article_slug', $article->slug, 'text');
                            } ?>
                        <?php $value = (isset($article) ? $article->articlegroup : ''); ?>
                        <?php if (has_permission('knowledge_base', '', 'create')) {
                                echo render_select_with_input_group('articlegroup', get_kb_groups(), ['groupid', 'name'], 'kb_article_add_edit_group', $value, '<div class="input-group-btn"><a href="#" class="btn btn-default" onclick="new_kb_group();return false;"><i class="fa fa-plus"></i></a></div>');
                            } else {
                                echo render_select('articlegroup', get_kb_groups(), ['groupid', 'name'], 'kb_article_add_edit_group', $value);
                            }
                     ?>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="staff_article" name="staff_article" <?php if (isset($article) && $article->staff_article == 1) {
                         echo 'checked';
                     } ?>>
                            <label for="staff_article"><?php echo _l('internal_article'); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="disabled" name="disabled" <?php if (isset($article) && $article->active_article == 0) {
                         echo 'checked';
                     } ?>>
                            <label for="disabled"><?php echo _l('kb_article_disabled'); ?></label>
                        </div>
                        <p class="bold"><?php echo _l('kb_article_description'); ?></p>
                        <?php $contents = ''; if (isset($article)) {
                         $contents      = $article->description;
                     } ?>
                        <?php echo render_textarea('description', '', $contents, [], [], '', 'tinymce tinymce-manual'); ?>
                    </div>
                    <?php if ((has_permission('knowledge_base', '', 'create') && !isset($article)) || has_permission('knowledge_base', '', 'edit') && isset($article)) { ?>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <?php echo _l('submit'); ?>
                        </button>
                    </div>
                    <?php } ?>
                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php $this->load->view('admin/knowledge_base/group'); ?>
<?php init_tail(); ?>
<script>
$(function() {
    init_editor('#description', {
        append_plugins: 'stickytoolbar'
    });
    appValidateForm($('#article-form'), {
        subject: 'required',
        articlegroup: 'required'
    });
});
</script>
</body>

</html>