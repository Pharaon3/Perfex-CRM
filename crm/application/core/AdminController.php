<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AdminController extends App_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ($this->app->is_db_upgrade_required($this->current_db_version)) {
            if ($this->input->post('upgrade_database')) {
                hooks()->do_action('pre_upgrade_database');

                $this->app->upgrade_database();
            }

            die(include_once(VIEWPATH . 'admin/includes/db_update_required.php'));
        }

        hooks()->do_action('pre_admin_init');

        if (!is_staff_logged_in()) {
            if (strpos(current_full_url(), get_admin_uri() . '/authentication') === false) {
                redirect_after_login_to_current_url();
            }

            redirect(admin_url('authentication'));
        }

        if ($this->uri->segment(3) != 'notifications_check') {
            // In case staff have setup logged in as client - This is important don't change it
            foreach (['client_user_id', 'contact_user_id', 'client_logged_in', 'logged_in_as_client'] as $sk) {
                if ($this->session->has_userdata($sk)) {
                    $this->session->unset_userdata($sk);
                }
            }
        }

        // Update staff last activity
        $this->db->where('staffid', get_staff_user_id());
        $this->db->update('staff', ['last_activity' => date('Y-m-d H:i:s')]);

        $this->load->model('staff_model');

        // Do not check on ajax requests
        if (!$this->input->is_ajax_request()) {
            if (ENVIRONMENT == 'production' && is_admin()) {
                if ($this->config->item('encryption_key') === '') {
                    die('<h1>Encryption key not sent in application/config/app-config.php</h1>For more info visit <a href="https://help.perfexcrm.com/encryption-key-explained/">encryption key explained</a>');
                } elseif (strlen($this->config->item('encryption_key')) != 32) {
                    die('<h1>Encryption key length should be 32 charachters</h1>For more info visit <a href="https://help.perfexcrm.com/encryption-key-explained/">encryption key explained</a>');
                }
            }

            _maybe_system_setup_warnings();

            $this->init_quick_actions_links();
        }

        $currentUser = $this->staff_model->get(get_staff_user_id());

        // Deleted or inactive but have session
        if (!$currentUser || $currentUser->active == 0) {
            $this->authentication_model->logout();
            redirect(admin_url('authentication'));
        }

        $GLOBALS['current_user'] = $currentUser;

        init_admin_assets();

        hooks()->do_action('admin_init');

        $vars = [
            'current_user'    => $currentUser,
            'current_version' => $this->current_db_version,
            'task_statuses'   => $this->tasks_model->get_statuses(),
        ];

        if (!$this->input->is_ajax_request()) {
            $vars['sidebar_menu'] = $this->app_menu->get_sidebar_menu_items();
            $vars['setup_menu']   = $this->app_menu->get_setup_menu_items();
        }

        /**
        * Autoloaded view variables
        * @var array
        */
        $vars = hooks()->apply_filters('admin_area_auto_loaded_vars', $vars);
        $this->load->vars($vars);
    }

    private function init_quick_actions_links()
    {
        $this->app->add_quick_actions_link([
            'name'       => _l('invoice'),
            'permission' => 'invoices',
            'url'        => 'invoices/invoice',
            'position'   => 5,
            'icon'       => 'fa-solid fa-file-invoice',
        ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('estimate'),
            'permission' => 'estimates',
            'url'        => 'estimates/estimate',
            'position'   => 10,
            'icon'       => 'fa-regular fa-file',
        ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('proposal'),
            'permission' => 'proposals',
            'url'        => 'proposals/proposal',
            'position'   => 15,
            'icon'       => 'fa-regular fa-file-powerpoint',
        ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('credit_note'),
            'permission' => 'credit_notes',
            'url'        => 'credit_notes/credit_note',
            'position'   => 20,
            'icon'       => 'fa-regular fa-file-lines',
        ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('client'),
            'permission' => 'customers',
            'url'        => 'clients/client',
            'position'   => 25,
            'icon'       => 'fa-regular fa-building',
        ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('subscription'),
            'permission' => 'subscriptions',
            'url'        => 'subscriptions/create',
            'position'   => 30,
            'icon'       => 'fa-solid fa-repeat',
        ]);


        $this->app->add_quick_actions_link([
            'name'       => _l('project'),
            'url'        => 'projects/project',
            'permission' => 'projects',
            'position'   => 35,
            'icon'       => 'fa-solid fa-chart-gantt',
        ]);


        $this->app->add_quick_actions_link([
            'name'            => _l('task'),
            'url'             => '#',
            'custom_url'      => true,
            'href_attributes' => [
                'onclick' => 'new_task();return false;',
            ],
            'permission' => 'tasks',
            'position'   => 40,
            'icon'       => 'fa-regular fa-circle-check',
        ]);

        $this->app->add_quick_actions_link([
            'name'            => _l('lead'),
            'url'             => '#',
            'custom_url'      => true,
            'permission'      => 'is_staff_member',
            'href_attributes' => [
                'onclick' => 'init_lead(); return false;',
            ],
            'position' => 45,
            'icon'     => 'fa-solid fa-tty',
        ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('expense'),
            'permission' => 'expenses',
            'url'        => 'expenses/expense',
            'position'   => 50,
            'icon'       => 'fa-solid fa-file-lines',
        ]);


        $this->app->add_quick_actions_link([
            'name'       => _l('contract'),
            'permission' => 'contracts',
            'url'        => 'contracts/contract',
            'position'   => 55,
            'icon'       => 'fa-solid fa-file-contract',
        ]);


        $this->app->add_quick_actions_link([
            'name'       => _l('kb_article'),
            'permission' => 'knowledge_base',
            'url'        => 'knowledge_base/article',
            'position'   => 60,
            'icon'       => 'fa-solid fa-circle-info',
        ]);

        $tickets = [
            'name'     => _l('ticket'),
            'url'      => 'tickets/add',
            'position' => 65,
            'icon'     => 'fa-solid fa-life-ring',
        ];

        if (get_option('access_tickets_to_none_staff_members') == 0 && !is_staff_member()) {
            $tickets['permission'] = 'is_staff_member';
        }

        $this->app->add_quick_actions_link($tickets);

        $this->app->add_quick_actions_link([
            'name'       => _l('staff_member'),
            'url'        => 'staff/member',
            'permission' => 'staff',
            'position'   => 70,
            'icon'       => 'fa-regular fa-user',
        ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('calendar_event'),
            'url'        => 'utilities/calendar?new_event=true&date=' . _d(date('Y-m-d')),
            'permission' => '',
            'position'   => 75,
            'icon'       => 'fa-regular fa-calendar',
        ]);
    }
}