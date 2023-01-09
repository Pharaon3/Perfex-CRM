<h4 class="bold text-success">Installation successful!</h4>

<?php if (isset($config_copy_failed)) { ?>
<p class="text-danger">
    Failed to copy application/config/app-config-sample.php. Please navigate to
    application/config/
    and copy the file app-config-sample.php and rename it to app-config.php.
</p>
<?php } ?>

<p>
    <b>Delete the install directory</b> and login as administrator at <a href="<?php echo $_POST['base_url']; ?>admin"
        target="_blank"><?php echo $_POST['base_url']; ?>admin</a>.
</p>

<hr />

<h4><b style="color:red;">Remember</b></h4>

<ul class="list-unstyled">
    <li>Administrators/staff members must login at <a href="<?php echo $_POST['base_url']; ?>admin"
            target="_blank"><?php echo $_POST['base_url']; ?>admin</a>.</li>
    <li>Customers contacts must login at <a href="<?php echo $_POST['base_url']; ?>clients"
            target="_blank"><?php echo $_POST['base_url']; ?>clients</a>.</li>
</ul>

<hr />

<h4>
    <b>
        404 Not Found After Installation? - <a href="https://help.perfexcrm.com/404-not-found-after-installation/"
            target="_blank">
            Read more
        </a>
    </b>
</h4>
<hr />
<h4>
    <b>Getting Started Guide - <a href="https://help.perfexcrm.com/quick-installation-getting-started-tutorial/"
            target="_blank">
            Read more
        </a>
    </b>
</h4>
<hr />
<h4>
    <b>Looking For Help? - <a href="https://my.perfexcrm.com/" target="_blank">
            Open Support Ticket
        </a>
    </b>
</h4>