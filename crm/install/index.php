<?php

require_once('install.class.php');
$install = new Install();

if (file_exists($install->config_path) &&
        (!isset($_POST['step']) || isset($_POST['step']) && $_POST['step'] !== Install::$last_step)) {
    echo '<h1>The installation is already finished!</h1>';
    echo '<p style="font-size:18px;font-family:monospace;">If the installation failed for some reason and you need to repeat the installation steps first delete the <code><strong>application/config/app-config.php</strong></code> file and then <strong>delete all tables from the database</strong> e.q. via phpmyadmin and repeat the installation steps again.</p>';
    exit(0);
}

$install->go();