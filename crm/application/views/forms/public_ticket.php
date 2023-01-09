<div class="public-ticket mtop40">
    <?php hooks()->do_action('public_ticket_start', $ticket); ?>
    <?php if (is_staff_logged_in()) { ?>
    <div class="alert alert-warning">
        You are logged in a staff member, if you want to reply to the ticket as staff, you must make reply via the admin
        area.
    </div>
    <?php } ?>
    <?php echo $single_ticket_view; ?>
    <?php hooks()->do_action('public_ticket_end', $ticket); ?>
</div>