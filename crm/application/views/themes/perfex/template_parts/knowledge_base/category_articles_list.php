<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <div class="tw-divide-y tw-divide-neutral-200 tw-divide-solid">
        <?php foreach ($articles as $category) { ?>
        <div class="tw-pt-5 first:tw-pt-0 tw-block">
            <ul class="list-unstyled articles_list tw-space-y-5">
                <?php foreach ($category['articles'] as $article) { ?>
                <li>
                    <div class="sm:tw-flex sm:tw-justify-between">
                        <h4 class="tw-text-lg tw-font-medium tw-my-0">
                            <a href="<?php echo site_url('knowledge-base/article/' . $article['slug']); ?>"
                                class="tw-text-neutral-600 hover:tw-text-neutral-800 active:tw-text-neutral-800">
                                <?php echo $article['subject']; ?>
                            </a>
                        </h4>
                        <span class="tw-text-neutral-500 tw-text-xs tw-self-start">
                            <?php echo _dt($article['datecreated']); ?>
                        </span>
                    </div>
                    <div class="tw-text-neutral-500 tw-mt-4 sm:tw-mt-0">
                        <?php echo strip_tags(mb_substr($article['description'], 0, 250)); ?>...
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    </div>
</div>