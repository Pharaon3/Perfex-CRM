<?php defined('BASEPATH') or exit('No direct script access allowed');
if (count($articles) > 0) {
    ?>
<div class="col-md-12 kb-search-results">
    <h2 class="tw-font-medium tw-text-lg tw-mt-0"><?php echo $title; ?></h2>
</div>
<?php
    get_template_part('knowledge_base/category_articles_list', ['articles' => $articles]);
}