<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_300 extends CI_Migration
{
    public function up()
    {
        $projectSettings = $this->db
            ->where('name', 'available_features')
            ->get(db_prefix() . 'project_settings')
            ->result_array();

        foreach ($projectSettings as $availableFeature) {
            @$setting = unserialize($availableFeature['value']);
            if (is_array($setting) && !array_key_exists('project_proposals', $setting)) {
                $setting['project_proposals'] = 1;
                $this->db->where('id', $availableFeature['id']);
                $this->db->update(db_prefix() . 'project_settings', ['value' => serialize($setting)]);
            }
        }
    }
}