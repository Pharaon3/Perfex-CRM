<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<table class="table dt-table table-ticket-reports" data-order-col="0" data-order-type="asc">
    <thead>
    <tr>
        <th><?php echo _l('ticket_reports_staff'); ?></th>
        <th><?php echo _l('ticket_reports_total_assigned'); ?></th>
        <th><?php echo _l('ticket_reports_open_tickets'); ?></th>
        <th><?php echo _l('ticket_reports_closed_tickets'); ?></th>
        <th><?php echo _l('ticket_reports_replies_to_tickets'); ?></th>
        <th>
            <i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="<?php echo _l('ticket_reports_average_reply_time_help') ?>" data-placement="top"></i>
            <?php echo _l('ticket_reports_average_reply_time'); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tickets_report as $staff) { ?>
        <tr>
            <td>
                <a href="<?php
                echo admin_url("staff/member/$staff->staffid"); ?>">
                    <?php
                    echo "$staff->firstname $staff->lastname"; ?>
                </a>
            </td>
            <td><?php echo $staff->total_assigned; ?></td>
            <td><?php echo $staff->total_open_tickets; ?></td>
            <td><?php echo $staff->total_closed_tickets; ?></td>
            <td><?php echo $staff->total_replies; ?></td>
            <td><?php echo $staff->average_reply_time; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
