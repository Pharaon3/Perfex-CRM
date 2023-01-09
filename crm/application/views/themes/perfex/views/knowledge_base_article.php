<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="section-knowledge-base">
    <div class="row">
        <div class="col-md-<?php echo count($related_articles) == 0 ? 12 : 8; ?>">
            <div class="panel_s">
                <div class="panel-body">
                    <h1
                        class="tw-mt-0 tw-mb-4 kb-article-single-heading tw-font-semibold tw-text-xl tw-text-neutral-700">
                        <?php echo $article->subject; ?>
                    </h1>
                    <div class="tc-content kb-article-content tw-text-neutral-700">
                        <?php echo $article->description; ?>
                    </div>
                    <h4 class="tw-font-medium tw-text-lg tw-mt-6">
                        <?php echo _l('clients_knowledge_base_find_useful'); ?>
                    </h4>
                    <div class="answer_response tw-mb-2 tw-text-neutral-500"></div>
                    <div class="btn-group article_useful_buttons" role="group">
                        <button type="button" data-answer="1" class="btn btn-success">
                            <?php echo _l('clients_knowledge_base_find_useful_yes'); ?>
                        </button>
                        <input type="hidden" name="articleid" value="<?php echo $article->articleid; ?>">
                        <button type="button" data-answer="0" class="btn btn-danger">
                            <?php echo _l('clients_knowledge_base_find_useful_no'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php hooks()->do_action('after_single_knowledge_base_article_customers_area', $article->articleid); ?>
        </div>
        <?php if (count($related_articles) > 0) { ?>
        <div class="col-md-4">
            <h4 class="kb-related-heading tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mt-0 tw-my-0">
                <?php echo _l('related_knowledgebase_articles'); ?>
            </h4>
            <ul class="articles_list tw-divide-y tw-divide-neutral-200 tw-divide-solid tw-space-y-3">
                <?php foreach ($related_articles as $relatedArticle) { ?>
                <li class="tw-pt-3">
                    <h4 class="article-heading article-related-heading tw-text-normal tw-font-medium tw-my-0">
                        <a href="<?php echo site_url('knowledge-base/article/' . $relatedArticle['slug']); ?>"
                            class="tw-text-neutral-700 hover:tw-text-neutral-900 active:tw-text-neutral-900">
                            <?php echo $relatedArticle['subject']; ?>
                        </a>
                    </h4>
                    <div class="tw-text-neutral-500">
                        <?php echo mb_substr(strip_tags($relatedArticle['description']), 0, 100); ?>...
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php }	?>
    </div>
</div>