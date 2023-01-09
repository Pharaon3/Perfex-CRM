<?php
$error = false;
if (!is_writable('../uploads/estimates')) {
    $error                 = true;
    $requirement_estimates = "<span class='label label-danger'>No (Make uploads/estimates writable) - Permissions 0755</span>";
} else {
    $requirement_estimates = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/proposals')) {
    $error                 = true;
    $requirement_proposals = "<span class='label label-danger'>No (Make uploads/proposals writable) - Permissions 0755</span>";
} else {
    $requirement_proposals = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/ticket_attachments')) {
    $error        = true;
    $requirement1 = "<span class='label label-danger'>No (Make uploads/ticket_attachments writable) - Permissions 0755</span>";
} else {
    $requirement1 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/tasks')) {
    $error        = true;
    $requirement2 = "<span class='label label-danger'>No (Make uploads/tasks writable) - Permissions 0755</span>";
} else {
    $requirement2 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/staff_profile_images')) {
    $error        = true;
    $requirement3 = "<span class='label label-danger'>No (Make uploads/staff_profile_images writable) - Permissions 0755</span>";
} else {
    $requirement3 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/projects')) {
    $error        = true;
    $requirement4 = "<span class='label label-danger'>No (Make uploads/projects writable) - Permissions 0755</span>";
} else {
    $requirement4 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/newsfeed')) {
    $error        = true;
    $requirement5 = "<span class='label label-danger'>No (Make uploads/newsfeed writable) - Permissions 0755</span>";
} else {
    $requirement5 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/leads')) {
    $error        = true;
    $requirement6 = "<span class='label label-danger'>No (Make uploads/leads writable) - Permissions 0755</span>";
} else {
    $requirement6 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/invoices')) {
    $error        = true;
    $requirement7 = "<span class='label label-danger'>No (Make uploads/invoices writable) - Permissions 0755</span>";
} else {
    $requirement7 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/expenses')) {
    $error        = true;
    $requirement8 = "<span class='label label-danger'>No (Make uploads/expenses writable) - Permissions 0755</span>";
} else {
    $requirement8 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/discussions')) {
    $error        = true;
    $requirement9 = "<span class='label label-danger'>No (Make uploads/discussions writable) - Permissions 0755</span>";
} else {
    $requirement9 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/contracts')) {
    $error         = true;
    $requirement10 = "<span class='label label-danger'>No (Make uploads/contracts writable) - Permissions 0755</span>";
} else {
    $requirement10 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/company')) {
    $error         = true;
    $requirement11 = "<span class='label label-danger'>No (Make uploads/company writable) - Permissions 0755</span>";
} else {
    $requirement11 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/clients')) {
    $error         = true;
    $requirement12 = "<span class='label label-danger'>No (Make uploads/clients writable) - Permissions 0755</span>";
} else {
    $requirement12 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../uploads/client_profile_images')) {
    $error         = true;
    $requirement13 = "<span class='label label-danger'>No (Make uploads/client_profile_images writable) - Permissions 0755</span>";
} else {
    $requirement13 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../application/config')) {
    $error         = true;
    $requirement14 = "<span class='label label-danger'>No (Make application/config/ writable - Permissions 0755</span>";
} else {
    $requirement14 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../application/config/config.php')) {
    $error         = true;
    $requirement15 = "<span class='label label-danger'>No (Make application/config/config.php writable) - Permissions 0644</span>";
} else {
    $requirement15 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../application/config/app-config-sample.php')) {
    $error         = true;
    $requirement16 = "<span class='label label-danger'>No (Make application/config/app-config-sample.php writable) - Permissions - 0644</span>";
} else {
    $requirement16 = "<span class='label label-success'>Ok</span>";
}
if (!is_writable('../temp')) {
    $error         = true;
    $requirement17 = "<span class='label label-danger'>No (Make temp folder writable) - Permissions 0755</span>";
} else {
    $requirement17 = "<span class='label label-success'>Ok</span>";
}

?>
<table class="table table-hover tw-text-sm">
    <thead>
        <tr>
            <th><b>File/Folder</b></th>
            <th><b>Result</b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="tw-font-medium">uploads/proposals</td>
            <td><?php echo $requirement_proposals; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/estimates</td>
            <td><?php echo $requirement_estimates; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/ticket_attachments</td>
            <td><?php echo $requirement1; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/tasks</td>
            <td><?php echo $requirement2; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/staff_profile_images</td>
            <td><?php echo $requirement3; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/projects</td>
            <td><?php echo $requirement4; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/newsfeed</td>
            <td><?php echo $requirement5; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/leads</td>
            <td><?php echo $requirement6; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/invoices</td>
            <td><?php echo $requirement7; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/expenses</td>
            <td><?php echo $requirement8; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/discussions</td>
            <td><?php echo $requirement9; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/contracts</td>
            <td><?php echo $requirement10; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/company</td>
            <td><?php echo $requirement11; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/clients</td>
            <td><?php echo $requirement12; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">uploads/client_profile_images</td>
            <td><?php echo $requirement13; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">application/config Writable</td>
            <td><?php echo $requirement14; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">config.php Writable</td>
            <td><?php echo $requirement15; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">app-config-sample.php Writable (Auto Updated & Renamed on Install)</td>
            <td><?php echo $requirement16; ?></td>
        </tr>
        <tr>
            <td class="tw-font-medium">/temp folder Writable</td>
            <td><?php echo $requirement17; ?></td>
        </tr>
    </tbody>
</table>
<hr class="-tw-mx-4" />
<?php if ($error == true) {
    echo '<div class="text-center alert alert-danger tw-mb-0">You need to fix the requirements in order to install Perfex CRM</div>';
} else {
    echo '<div class="text-center">';
    echo '<form action="" method="post" accept-charset="utf-8">';
    echo '<input type="hidden" name="permissions_success" value="true">';
    echo '<div class="text-right">';
    echo '<button type="submit" class="btn btn-primary">Setup Database</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
}