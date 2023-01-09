<?php

defined('BASEPATH') or exit('No direct script access allowed');

class App_Input extends CI_Input
{
    /**
     * Fetch the IP Address
     *
     * Determines and validates the visitor's IP address.
     *
     * @see  https://docs.sucuri.net/website-firewall/troubleshooting/same-ip-for-all-users/#codeigniter
     *
     * @return  string  IP address
     */
    public function ip_address()
    {
        if (isset($_SERVER['HTTP_X_SUCURI_CLIENTIP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_SUCURI_CLIENTIP'];
        }

        return parent::ip_address();
    }

    /**
     * Validate IP Address
     *
     * @param   string  $ip IP address
     * @param   string  $which  IP protocol: 'ipv4' or 'ipv6'
     * @return  bool
     */
    public function valid_ip($ip, $which = '')
    {
        switch (strtolower($which)) {
            case 'ipv4':
                $which = FILTER_FLAG_IPV4;

                break;
            case 'ipv6':
                $which = FILTER_FLAG_IPV6;

                break;
            default:
                $which = 0;

                break;
        }

        return (bool) filter_var($ip, FILTER_VALIDATE_IP, $which);
    }
}
