<?php

namespace app\modules\exports\services;

use CI_DB_mysqli_driver;

class ContactCSVExport extends CSVExport
{
    private string $defaultDirection;

    public function __construct(?string $fromDate, ?string $toDate)
    {
        parent::__construct($fromDate, $toDate, 'contacts');
        $this->defaultDirection = get_option('rtl_support_client') == 1 ? 'rtl' : 'ltr';
    }

    private array $excludedFields = [
        'new_pass_key',
        'new_pass_key_requested',
        'email_verification_key',
        'email_verification_sent_at',
        'password',
        'last_password_change',
        'last_ip',
        'userid',
    ];

    public function queryData(): CI_DB_mysqli_driver
    {
        $this->ci->db->select(prefixed_table_fields_array(db_prefix() . 'contacts', true, $this->excludedFields) . ',' . get_sql_select_client_company());
        $this->selectCustomFields(db_prefix() . 'contacts.id');
        $this->ci->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'contacts.userid', 'left');
        $this->applyDateFilter(db_prefix() . 'contacts.datecreated');

        return $this->ci->db->from(db_prefix() . 'contacts');
    }

    protected function formatRow(string $name, $value, array $row)
    {
        switch ($name) {
            case 'profile_image':
                return $this->getProfileImageUrl($row['id'], $value);
            case 'direction':
                return $value ?: $this->defaultDirection;
            default:
                return parent::formatRow($name, $value, $row);
        }
    }

    private function getProfileImageUrl(int $contactId, ?string $profileImage): ?string
    {
        if (!empty($profileImage)) {
            $path = 'uploads/client_profile_images/' . $contactId . '/small_' . $profileImage;

            if (file_exists($path)) {
                return base_url($path);
            }
        }

        return '';
    }
}
