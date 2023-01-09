<?php

defined('BASEPATH') or exit('No direct script access allowed');

class InitModules
{
    /**
     * Early init modules features
     */
    public function handle()
    {
        include_once(__DIR__ . './../libraries/App_modules.php');
        // Load the directory helper so the directory_map function can be used
        include_once(__DIR__ . './../../system/helpers/directory_helper.php');

        foreach (\App_modules::get_valid_modules() as $module) {
            $excludeUrisPath = $module['path'] . 'config' . DIRECTORY_SEPARATOR . 'csrf_exclude_uris.php';

            if (file_exists($excludeUrisPath)) {
                $uris = include_once($excludeUrisPath);

                if (is_array($uris)) {
                    hooks()->add_filter('csrf_exclude_uris', function ($current) use ($uris) {
                        return array_merge($current, $uris);
                    });
                }
            }
        }
    }
}
