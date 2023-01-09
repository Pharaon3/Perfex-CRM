<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_hidden('settings[customer_settings]', 'true'); ?>
<div class="form-group">
	<label for="clients_default_theme" class="control-label"><?php echo _l('settings_clients_default_theme'); ?></label>
	<select name="settings[clients_default_theme]" id="clients_default_theme" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
		<?php foreach (get_all_client_themes() as $theme) { ?>
		<option value="<?php echo $theme; ?>" <?php if (active_clients_theme() == $theme) {
    echo 'selected';
} ?>><?php echo ucfirst($theme); ?></option>
		<?php } ?>
	</select>
</div>
<hr />
<?php echo render_select('settings[customer_default_country]', get_all_countries(), [ 'country_id', [ 'short_name']], 'customer_default_country', get_option('customer_default_country')); ?>
<hr />
<?php
    $tabs         = get_customer_profile_tabs();
    $current_tabs = get_option('visible_customer_profile_tabs');
    if ($current_tabs != 'all') {
        $current_tabs = unserialize($current_tabs);
    }
?>
<div class="form-group">
	<label for="visible_customer_profile_tabs" class="control-label"><?php echo _l('visible_tabs'); ?> (<?php echo _l('client_add_edit_profile'); ?>)</label>
	<select name="settings[visible_customer_profile_tabs][]" id="visible_customer_profile_tabs" multiple class="form-control selectpicker" data-none-selected-text="<?php echo _l('all'); ?>" data-actions-box="true">
		<?php foreach ($tabs as $tabKey => $tab) {
    if ($tabKey == 'profile' || $tabKey == 'contacts') {
        continue;
    } ?>
			<option value="<?php echo $tabKey; ?>"<?php if ((is_array($current_tabs)
            && array_key_exists($tabKey, $current_tabs) && $current_tabs[$tabKey] == true) || (is_array($current_tabs) && !array_key_exists($tabKey, $current_tabs))) {
        echo ' selected';
    } ?>>
				<?php echo $tab['name']; ?>
			</option>
		<?php
} ?>
	</select>
</div>
<hr />
<?php render_yes_no_option('company_is_required', 'company_is_required'); ?>
<hr />
<?php render_yes_no_option('company_requires_vat_number_field', 'company_requires_vat_number_field'); ?>
<hr />
<?php render_yes_no_option('allow_registration', 'settings_clients_allow_registration'); ?>
<hr />
<?php render_yes_no_option('customers_register_require_confirmation', 'customers_register_require_confirmation'); ?>
<hr />
<?php render_yes_no_option('allow_primary_contact_to_manage_other_contacts', 'allow_primary_contact_to_manage_other_contacts'); ?>
<hr />
<?php render_yes_no_option('enable_honeypot_spam_validation', 'enable_honeypot_spam_validation'); ?>
<hr />
<?php render_yes_no_option('allow_primary_contact_to_view_edit_billing_and_shipping', 'allow_primary_contact_to_view_edit_billing_and_shipping'); ?>
<hr />
<?php render_yes_no_option('only_own_files_contacts', 'only_own_files_contacts', 'only_own_files_contacts_help'); ?>
<hr />
<?php render_yes_no_option('allow_contact_to_delete_files', 'allow_contact_to_delete_files'); ?>
<hr />
<?php render_yes_no_option('use_knowledge_base', 'settings_general_use_knowledgebase', 'settings_general_use_knowledgebase_tooltip'); ?>
<hr />
<?php render_yes_no_option('knowledge_base_without_registration', 'settings_clients_allow_kb_view_without_registration'); ?>
<hr />
<div class="form-group">
	<?php
    $this->load->model('estimate_request_model');
    $estimateRequestForms = $this->estimate_request_model->get_forms();
    ?>
	<label for="show_estimate_request_in_customers_area"
		class="control-label">
		<?php echo _l('show_estimate_request_in_customers_area'); ?>
	</label>
	<select name="settings[show_estimate_request_in_customers_area]"
		id="show_estimate_request_in_customers_area"
		class="form-control selectpicker"
		data-none-selected-text="<?php echo _l('settings_no'); ?>">
			<option value="0"<?php if (get_option('show_estimate_request_in_customers_area') == 0) {
        echo ' selected';
    } ?>>
				<?php echo _l('settings_no'); ?>
			</option>
		<?php foreach ($estimateRequestForms as $form) { ?>
			<option value="<?php echo $form['id']; ?>"<?php if (get_option('show_estimate_request_in_customers_area') == $form['id']) {
        echo ' selected';
    } ?>>
				<?php echo $form['name']; ?>
			</option>
		<?php } ?>
	</select>
</div>
<?php $default_contact_permissions = unserialize(get_option('default_contact_permissions')); ?>
<div class="form-group">
	<label for="" class="control-label"><?php echo _l('default_contact_permissions'); ?></label>
	<?php foreach ($contacts_permissions as $p) { ?>
	<div class="checkbox checkbox-primary">
		<input type="checkbox" name="settings[default_contact_permissions][]" <?php if (is_array($default_contact_permissions) && in_array($p['id'], $default_contact_permissions)) {
        echo 'checked';
    } ?> id="dcp_<?php echo $p['id']; ?>" value="<?php echo $p['id']; ?>">
		<label for="dcp_<?php echo $p['id']; ?>"><?php echo $p['name']; ?></label>
	</div>
	<?php } ?>
</div>
<hr />
<i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip" data-title="<?php echo _l('invoices') . ', ' . _l('estimates') . ', ' . _l('payments') . ', ' . _l('customer_statement'); ?>"></i>
<?php echo render_textarea('settings[customer_info_format]', 'customer_info_format', clear_textarea_breaks(get_option('customer_info_format')), ['rows' => 8, 'style' => 'line-height:20px;']); ?>
<p>
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{company_name}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{customer_id}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{street}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{city}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{state}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{zip_code}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{country_code}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{country_name}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{phone}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{vat_number}</a>,
	<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{vat_number_with_label}</a>

</p>
<?php $custom_fields = get_custom_fields('customers');
if (count($custom_fields) > 0) {
    echo '<hr />';
    echo '<p class="no-mbot font-medium"><b>' . _l('custom_fields') . '</b></p>';
    if (total_rows(db_prefix() . 'customfields', ['fieldto' => 'customers', 'show_on_client_portal' => 1]) == 0) {
        echo '<p>' . _l('custom_field_pdf_html_help') . '</p>';
        echo '<hr />';
    }
    echo '<ul class="list-group">';
    foreach ($custom_fields as $field) {
        echo '<li class="list-group-item"><b>' . $field['name'] . '</b>: ' . '<a href="#" class="settings-textarea-merge-field" data-to="customer_info_format">{cf_' . $field['id'] . '}</a></li>';
    }
    echo '</ul>';
    echo '<hr />';
}
?>
