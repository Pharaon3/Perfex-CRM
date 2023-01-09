<?php

$error = false;

if (version_compare(PHP_VERSION, '7.4') >= 0) {
    $requirement1 = "<span class='label label-success'>v." . PHP_VERSION . '</span>';
} else {
    $error        = true;
    $requirement1 = "<span class='label label-danger'>Your PHP version is " . PHP_VERSION . '</span>';
}

if (!extension_loaded('mysqli')) {
    $error        = true;
    $requirement2 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement2 = "<span class='label label-success'>Enabled</span>";
}

if (!extension_loaded('pdo')) {
    $error        = true;
    $requirement3 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement3 = "<span class='label label-success'>Enabled</span>";
}

if (!extension_loaded('curl')) {
    $error        = true;
    $requirement4 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement4 = "<span class='label label-success'>Enabled</span>";
}

if (!extension_loaded('openssl')) {
    $error        = true;
    $requirement5 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement5 = "<span class='label label-success'>Enabled</span>";
}

if (!extension_loaded('mbstring')) {
    $error        = true;
    $requirement6 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement6 = "<span class='label label-success'>Enabled</span>";
}

if (!extension_loaded('iconv') && !function_exists('iconv')) {
    $error        = true;
    $requirement7 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement7 = "<span class='label label-success'>Enabled</span>";
}

if (!extension_loaded('imap')) {
    $error        = true;
    $requirement8 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement8 = "<span class='label label-success'>Enabled</span>";
}

if (!extension_loaded('gd')) {
    $error        = true;
    $requirement9 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement9 = "<span class='label label-success'>Enabled</span>";
}

if (!extension_loaded('zip')) {
    $error         = true;
    $requirement10 = "<span class='label label-danger'>Zip Extension is not enabled</span>";
} else {
    $requirement10 = "<span class='label label-success'>Enabled</span>";
}

$url_f_open = ini_get('allow_url_fopen');
if ($url_f_open != '1'
    && strcasecmp($url_f_open, 'On') != 0
    && strcasecmp($url_f_open, 'true') != 0
    && strcasecmp($url_f_open, 'yes') != 0) {
    $error         = true;
    $requirement11 = "<span class='label label-danger'>Allow_url_fopen is not enabled!</span>";
} else {
    $requirement11 = "<span class='label label-success'>Enabled</span>";
}

?>
<table class="table table-hover tw-text-sm">
    <thead>
        <tr>
            <th><b>Extensions</b></th>
            <th><b>Result</b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="tw-font-medium">PHP >= 7.4</td>
            <td><?php echo $requirement1; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">MySQLi PHP Extension</td>
            <td><?php echo $requirement2; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">PDO PHP Extension</td>
            <td><?php echo $requirement3; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">cURL PHP Extension</td>
            <td><?php echo $requirement4; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">OpenSSL PHP Extension</td>
            <td><?php echo $requirement5; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">MBString PHP Extension</td>
            <td><?php echo $requirement6; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">iconv PHP Extension</td>
            <td><?php echo $requirement7; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">IMAP PHP Extension</td>
            <td><?php echo $requirement8; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">GD PHP Extension</td>
            <td><?php echo $requirement9; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">Zip PHP Extension</td>
            <td><?php echo $requirement10; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">allow_url_fopen</td>
            <td><?php echo $requirement11; ?></td>
        </tr>
    </tbody>
</table>
<hr class="-tw-mx-4" />
<?php if ($error == true) {
    echo '<div class="text-center alert alert-danger tw-mb-0">You need to fix the requirements in order to continue installing Perfex CRM</div>';
} else {
    echo '<div class="text-center">';
    echo '<form action="" method="post" accept-charset="utf-8">';
    echo '<input type="hidden" value="true" name="requirements_success">';
    echo '<div class="text-right">';
    echo '<button type="submit" class="btn btn-primary">Go to files/folders permissions</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
}