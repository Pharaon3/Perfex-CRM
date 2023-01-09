<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
  <?php
  foreach(filter_client_visible_tabs($customer_tabs, $client->userid) as $key => $tab){
    ?>
    <li class="<?php if($key == 'profile'){echo 'active ';} ?>customer_tab_<?php echo $key; ?>">
      <a data-group="<?php echo $key; ?>" href="<?php echo admin_url('clients/client/'.$client->userid.'?group='.$key); ?>">
        <?php if(!empty($tab['icon'])){ ?>
            <i class="<?php echo $tab['icon']; ?> menu-icon" aria-hidden="true"></i>
        <?php } ?>
        <?php echo $tab['name']; ?>
        <?php if (isset($tab['badge'], $tab['badge']['value']) && !empty($tab['badge'])) {?>
          <span class="badge pull-right 
            <?=isset($tab['badge']['type']) &&  $tab['badge']['type'] != '' ? "bg-{$tab['badge']['type']}" : 'bg-info' ?>"
              <?=(isset($tab['badge']['type']) &&  $tab['badge']['type'] == '') ||
                      isset($tab['badge']['color']) ? "style='background-color: {$tab['badge']['color']}'" : '' ?>>
              <?= $tab['badge']['value'] ?>
          </span>
        <?php } ?>
      </a>
    </li>
  <?php } ?>
</ul>
