<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="convert_estimate_request_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php echo form_open('admin/estimate_request/convert/' . $estimate_request->id, ['id' => 'request_to_client_form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo _l('convert_estimate_request'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php echo form_hidden('requestid', $estimate_request->id); ?>
                <?php echo form_hidden('default_language', $estimate_request->default_language); ?>
                <input type="hidden" name="convert_to">
                <div id="rel_wrapper">
                    <div class="form-group select-placeholder" id="rel_type_wrapper">
                        <label for="rel_type"
                            class="control-label"><?php echo _l('estimate_request_related'); ?></label>
                        <select name="rel_type" id="rel_type" class="selectpicker" data-width="100%"
                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                            <option value="lead"><?php echo _l('estimate_request_for_lead'); ?></option>
                            <option value="customer"><?php echo _l('estimate_request_for_customer'); ?></option>
                        </select>
                    </div>
                    <div class="form-group select-placeholder hide" id="rel_id_wrapper">
                        <label for="rel_id"><span class="rel_id_label"></span></label>
                        <div id="rel_id_select">
                            <select name="rel_id" id="rel_id" class="ajax-search" data-width="100%"
                                data-live-search="true"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            </select>
                        </div>
                    </div>
                </div>
                <?php if (staff_can('create', 'customers')) { ?>
                <a href="#" id="newConvertCustomer" class="display-block mbot10"><?php echo _l('new_client'); ?></a>
                <div id="create_customer_fields" class="hide">
                    <?php echo render_input('firstname', 'estimate_request_client_firstname'); ?>
                    <?php echo render_input('lastname', 'estimate_request_client_lastname'); ?>
                    <?php echo render_input('title', 'contact_position'); ?>
                    <?php echo render_input('email', 'client_email', $estimate_request->email); ?>

                    <?php echo render_custom_fields('contacts'); ?>

                    <?php echo render_input('company', 'client_company'); ?>
                    <?php echo render_input('phonenumber', 'client_phonenumber'); ?>
                    <?php echo render_input('website', 'client_website'); ?>
                    <?php echo render_textarea('address', 'client_address'); ?>
                    <?php echo render_input('city', 'client_city'); ?>
                    <?php echo render_input('state', 'client_state'); ?>
                    <?php
            $countries                = get_all_countries();
            $customer_default_country = get_option('customer_default_country');
            echo render_select('country', $countries, ['country_id', ['short_name']], 'clients_country', $customer_default_country, ['data-none-selected-text' => _l('dropdown_non_selected_tex')]);
            ?>
                    <?php echo render_input('zip', 'clients_zip'); ?>
                    <?php echo render_custom_fields('customers'); ?>
                    <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                    <input type="text" class="fake-autofill-field" name="fakeusernameremembered" value=''
                        tabindex="-1" />
                    <input type="password" class="fake-autofill-field" name="fakepasswordremembered" value=''
                        tabindex="-1" />

                    <div class="client_password_set_wrapper">
                        <label for="password" class="control-label"><?php echo _l('client_password'); ?></label>
                        <div class="input-group">
                            <input type="password" class="form-control password" name="password" autocomplete="off">
                            <span class="input-group-addon tw-border-l-0">
                                <a href="#password" class="show_password"
                                    onclick="showPassword('password');return false;"><i class="fa fa-eye"></i></a>
                            </span>
                            <span class="input-group-addon">
                                <a href="#" class="generate_password" onclick="generatePassword(this);return false;"><i
                                        class="fa fa-refresh"></i></a>
                            </span>
                        </div>
                    </div>
                    <?php if (is_email_template_active('contact-set-password')) { ?>
                    <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="send_set_password_email" id="send_set_password_email">
                        <label for="send_set_password_email">
                            <?php echo _l('client_send_set_password_email'); ?>
                        </label>
                    </div>
                    <?php } ?>
                    <?php if (is_email_template_active('new-client-created')) { ?>
                    <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="donotsendwelcomeemail" id="donotsendwelcomeemail">
                        <label for="donotsendwelcomeemail"><?php echo _l('client_do_not_send_welcome_email'); ?></label>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" data-form="#request_to_client_form" autocomplete="off"
                    data-loading-text="<?php echo _l('wait_text'); ?>"
                    class="btn btn-primary"><?php echo _l('convert'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>