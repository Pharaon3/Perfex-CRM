<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: CSV Export Manager
Description: Default module for Exporting data in CSV
Version: 1.0.0
Requires at least: 2.9.3
*/
define('EXPORTS_MODULE_NAME', 'exports');

register_language_files(EXPORTS_MODULE_NAME, [EXPORTS_MODULE_NAME]);
hooks()->add_action('admin_init', 'export_module_init_menu_items');

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function export_module_init_menu_items()
{
    $CI = &get_instance();
    if (is_admin()) {
        $CI->app_menu->add_sidebar_children_item('utilities', [
            'slug' => 'csv-export',
            'name' => _l('csv_export'),
            'href' => admin_url('exports'),
            'position' => 11,
        ]);
    }
}
