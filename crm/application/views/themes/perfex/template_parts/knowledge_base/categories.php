<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<ul role="list" class="tw-divide-y tw-divide-neutral-200">
    <?php foreach ($articles as $category) { ?>
    <li class="tw-py-2 last:tw-pb-0 first:tw-pt-0">
        <div class="tw-flex tw-items-center">
            <h3 class="tw-text-lg tw-font-medium tw-my-0 tw-text-neutral-700">
                <a href="<?php echo site_url('knowledge-base/category/' . $category['group_slug']); ?>"
                    class="tw-text-neutral-600 hover:tw-text-neutral-800 active:tw-text-neutral-800">
                    <?php echo $category['name']; ?>
                </a>
                <span class="badge tw-bg-neutral-50 tw-ml-1">
                    <?php echo count($category['articles']); ?>
                </span>
            </h3>
        </div>
        <p class="tw-text-neutral-500 tw-mb-0 tw-mt-1">
            <?php echo $category['description']; ?>
        </p>
    </li>
    <?php } ?>
</ul>