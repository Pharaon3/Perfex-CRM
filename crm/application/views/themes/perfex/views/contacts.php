<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="tw-flex tw-justify-between tw-items-end tw-mb-3">
    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-contacts">
        <?php echo _l('clients_my_contacts'); ?>
    </h4>
    <a href="<?php echo site_url('contacts/contact'); ?>" class="btn btn-primary">
        <?php echo _l('new_contact'); ?>
    </a>
</div>

<div class="panel_s">
    <div class="panel-body">
        <table class="table dt-table table-contacts" data-order-col="1" data-order-type="desc">
            <thead>
                <tr>
                    <th class="th-invoice-number"><?php echo _l('clients_list_full_name'); ?></th>
                    <th class="th-invoice-date"><?php echo _l('client_email'); ?></th>
                    <th class="th-invoice-duedate"><?php echo _l('contact_position'); ?></th>
                    <th class="th-invoice-amount"><?php echo _l('client_phonenumber'); ?></th>
                    <!-- <th class="th-invoice-status"><?php echo _l('contact_active'); ?></th> -->
                    <th class="th-invoice-status"><?php echo _l('clients_list_last_login'); ?></th>
                    <?php
                    $custom_fields = get_custom_fields('contact', ['show_on_client_portal' => 1]);
                    foreach ($custom_fields as $field) { ?>
                    <th><?php echo $field['name']; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($contacts as $contact) {
                    $rowName = '<img src="' . contact_profile_image_url($contact['id']) . '" class="client-profile-image-small mright5">' . get_contact_full_name($contact['id']);
                    $rowName .= '<div class="mleft25 pleft5 row-options">';
                    $rowName .= '<a href="' . site_url('contacts/contact/' . $contact['id']) . '">' . _l('edit') . '</a>';
                    if ($contact['is_primary'] == 0 || ($contact['is_primary'] == 1)) {
                        $rowName .= ' | <a href="' . site_url('contacts/delete/' . $contact['userid'] . '/' . $contact['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                    }
                    $rowName .= '</div> '; ?>
                <tr>
                    <td data-order="<?php echo get_contact_full_name($contact['id']); ?>"><?php echo $rowName; ?></td>
                    <td data-order="<?php echo $contact['email']; ?>"><?php echo $contact['email']; ?></td>
                    <td data-order="<?php echo $contact['title']; ?>"><?php echo $contact['title']; ?></td>
                    <td data-order="<?php echo $contact['phonenumber']; ?>"><a
                            href="tel:+<?php echo $contact['phonenumber']; ?>"><?php echo $contact['phonenumber']; ?></a>
                    </td>
                    <td data-order="<?php echo $contact['last_login'] ?>">
                        <?php
                            echo(!empty($aRow['last_login']) ? '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['last_login']) . '">' . time_ago($aRow['last_login']) . '</span>' : ''); ?>
                    </td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>
