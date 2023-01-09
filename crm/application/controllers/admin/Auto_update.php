<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auto_update extends AdminController
{
    public function index()
    {
        $purchase_key = trim($this->input->post('purchase_key', false));
        $latest_version = $this->input->post('latest_version');

        $url = UPDATE_URL . '?purchase_key=' . $purchase_key;

        hooks()->do_action('before_perform_update', $latest_version);

        $tmp_dir = get_temp_dir();

        if (!$tmp_dir || !is_writable($tmp_dir)) {
            $tmp_dir = app_temp_dir();
        }

        try {
            $this->checkPermissions();
            $config = new app\services\upgrade\Config(
                $purchase_key,
                $latest_version,
                $this->current_db_version,
                $url,
                $tmp_dir,
                FCPATH
            );

            if ($this->input->post('upgrade_function') === 'old') {
                $adapter = new app\services\upgrade\CurlCoreUpgradeAdapter();
            } else {
                $adapter = new app\services\upgrade\GuzzleCoreUpgradeAdapter();
            }

            $adapter->setConfig($config);
            $upgrade = new app\services\upgrade\UpgradeCore($adapter);

            $upgrade->perform();
        } catch (Exception $e) {
            header('HTTP/1.0 400 Bad error');
            echo json_encode([$e->getMessage()]);
        }
    }

    // Temporary function for v1.7.0 will be removed in a future, or perhaps not?

    /**
     * @throws Exception
     */
    private function checkPermissions()
    {
        $eMessage = 'The application could not write data into <strong>' . FCPATH;
        $eMessage .= '</strong> folder. Please give your web server user (<strong>' . get_current_user();
        $eMessage .= '</strong>) write permissions in <code>' . FCPATH . '</code> folder:<br/><br/>';
        $eMessage .= '<pre style="background: #f0f0f0;padding: 15px;width: 50%;  margin-top:0px; border-radius: 4px;">sudo chgrp ' . get_current_user() . ' ';
        $eMessage .= FCPATH . '<br/>sudo chmod ug+rwx ' . FCPATH . '</pre>';

        $directoryIterator = new RecursiveDirectoryIterator(FCPATH);

        foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
            if ($file->isFile() && !$file->isWritable() && strpos($file->getPathName(), '.git') === false) {
                throw new Exception($eMessage);
            }
        }

        return true;
    }

    public function database()
    {
    }
}
