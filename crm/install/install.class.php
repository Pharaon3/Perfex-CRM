<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

@ini_set('max_execution_time', 240);
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb');
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('PHPASS_HASH_STRENGTH', 8);
define('PHPASS_HASH_PORTABLE', false);

class Install
{
    protected $error = '';

    public $current_step = 1;

    public $config_path = '../application/config/app-config.php';

    public static $last_step = 4;

    public function go()
    {
        $debug = '';

        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST['step']) && $_POST['step'] == 2) {
                $this->current_step = 2;
            } elseif (isset($_POST['step']) && $_POST['step'] == 3) {
                if ($_POST['hostname'] == '') {
                    $this->error = 'Hostname is required';
                } elseif ($_POST['database'] == '') {
                    $this->error = 'Enter database name';
                } elseif ($_POST['password'] == '' && !$this->is_localhost()) {
                    $this->error = 'Enter database password';
                } elseif ($_POST['username'] == '') {
                    $this->error = 'Enter database username';
                }
                $this->current_step = 3;
                if ($this->error === '') {
                    $h = trim($_POST['hostname']);
                    $u = trim($_POST['username']);
                    $p = trim($_POST['password']);
                    $d = trim($_POST['database']);

                    $link = @new mysqli($h, $u, $p, $d);

                    if ($link->connect_errno) {
                        $this->error .= 'Error: Unable to connect to MySQL.<br />';
                        $this->error .= 'Debugging errno: ' . $link->connect_errno . '<br />';
                        $this->error .= 'Debugging error: ' . $link->connect_error;
                    } else {
                        $debug .= 'Success: A proper connection to MySQL was made! The ' . $d . ' database is great.<br />';
                        $debug .= 'Host information: ' . $link->host_info . '<br />';
                        $this->current_step = 4;
                        $link->close();
                    }
                }
            } elseif (isset($_POST['requirements_success'])) {
                $this->current_step = 2;
            } elseif (isset($_POST['permissions_success'])) {
                $this->current_step = 3;
            } elseif (isset($_POST['step']) && $_POST['step'] == 4) {
                if ($_POST['admin_email'] == '') {
                    $this->error = 'Enter admin email address';
                } elseif (filter_var($_POST['admin_email'], FILTER_VALIDATE_EMAIL) === false) {
                    $this->error = 'Enter valid email address';
                } elseif ($_POST['admin_password'] == '') {
                    $this->error = 'Enter admin password';
                } elseif ($_POST['admin_password'] != $_POST['admin_passwordr']) {
                    $this->error = 'Your password not match';
                } elseif ($_POST['base_url'] == '') {
                    $this->error = 'Please enter base url';
                }
                $this->current_step = 4;
            }
            if ($this->error === '' && isset($_POST['step']) && $_POST['step'] == 4) {
                include_once('sqlparser.php');
                $parser = new SqlScriptParser();

                $sqlStatements = $parser->parse('database.sql');

                $h = trim($_POST['hostname']);
                $u = trim($_POST['username']);
                $p = trim($_POST['password']);
                $d = trim($_POST['database']);

                $link = new mysqli($h, $u, $p, $d);

                foreach ($sqlStatements as $statement) {
                    $distilled = $parser->removeComments($statement);
                    if (!empty($distilled)) {
                        $link->query($distilled);
                    }
                }

                if (! $this->copy_app_config()) {
                    $config_copy_failed = true;
                }

                $this->write_app_config();

                require_once('phpass.php');

                $hasher    = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $password  = $hasher->HashPassword($_POST['admin_passwordr']);
                $email     = $link->escape_string($_POST['admin_email']);
                $firstname = $link->escape_string($_POST['firstname']);
                $lastname  = $link->escape_string($_POST['lastname']);

                $datecreated = date('Y-m-d H:i:s');

                // https://stackoverflow.com/questions/20867182/insert-query-executes-successfully-but-data-is-not-inserted-to-the-database
                // There is a commit in the database.sql
                $link->autocommit(true);

                $timezone = $_POST['timezone'];
                $sql      = "UPDATE tbloptions SET value='$timezone' WHERE name='default_timezone'";
                $link->query($sql);

                $di  = time();
                $sql = "UPDATE tbloptions SET value='$di' WHERE name='di'";
                $link->query($sql);

                $installMsg = '<div class="col-md-12">';
                $installMsg .= '<div class="alert alert-success">';
                $installMsg .= '<h4 class="bold">Congratulation on your installation!</h4>';
                $installMsg .= '<p>Now, you can activate modules that comes with the installation in <b>Setup->Modules<b>.</p>';
                $installMsg .= '</div>';
                $installMsg .= '</div>';

                $sql = "UPDATE tbloptions SET value='$installMsg' WHERE name='update_info_message'";
                $link->query($sql);

                $sql = "INSERT INTO tblstaff (`firstname`, `lastname`, `password`, `email`, `datecreated`, `admin`, `active`) VALUES('$firstname', '$lastname', '$password', '$email', '$datecreated', 1, 1)";
                $link->query($sql);

                if (!file_exists('../.htaccess') && is_writable('../')) {
                    fopen('../.htaccess', 'w');
                    $fp = fopen('../.htaccess', 'a+');
                    if ($fp) {
                        fwrite($fp, 'RewriteEngine on' . PHP_EOL . 'RewriteCond $1 !^(index\.php|resources|robots\.txt)' . PHP_EOL . 'RewriteCond %{REQUEST_FILENAME} !-f' . PHP_EOL . 'RewriteCond %{REQUEST_FILENAME} !-d' . PHP_EOL . 'RewriteRule ^(.*)$ index.php?/$1 [L,QSA]' . PHP_EOL . 'AddDefaultCharset utf-8');
                        fclose($fp);
                    }
                }
                $this->current_step = 5;
            } else {
                $error = $this->error;
            }
        }

        $current_step = $this->current_step;
        $steps        = $this->steps();

        require_once('html.php');
    }

    public function is_localhost()
    {
        $whitelist = [
            '127.0.0.1',
            '::1',
        ];

        if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            return true;
        }

        return false;
    }

    private function write_app_config()
    {
        $hostname = trim($_POST['hostname']);
        $database = trim($_POST['database']);
        $username = trim($_POST['username']);
        $password = addslashes(trim($_POST['password']));

        $base_url = trim($_POST['base_url']);
        $base_url = rtrim($base_url, '/') . '/';

        $encryption_key = bin2hex($this->create_key(16));
        $config_path    = $this->config_path;

        @chmod($config_path, FILE_WRITE_MODE);

        $config_file = file_get_contents($config_path);
        $config_file = trim($config_file);

        $config_file = str_replace('[db_hostname]', $hostname, $config_file);

        $config_file = str_replace('[db_username]', $username, $config_file);
        $config_file = str_replace('[db_password]', $password, $config_file);
        $config_file = str_replace('[db_name]', $database, $config_file);
        $config_file = str_replace('[encryption_key]', $encryption_key, $config_file);
        $config_file = str_replace('[base_url]', $base_url, $config_file);

        if (!$fp = fopen($config_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
            return false;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $config_file, strlen($config_file));
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($config_path, FILE_READ_MODE);

        return true;
    }

    private function copy_app_config()
    {
        if (@copy('../application/config/app-config-sample.php', '../application/config/app-config.php') == true) {
            return true;
        }

        return false;
    }

    public function create_key($length)
    {
        if (function_exists('random_bytes')) {
            try {
                return random_bytes((int) $length);
            } catch (Exception $e) {
                echo $e->getMessage();

                return false;
            }
        } elseif (defined('MCRYPT_DEV_URANDOM')) {
            return mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
        }

        $is_secure = null;
        $key       = openssl_random_pseudo_bytes($length, $is_secure);

        return ($is_secure === true) ? $key : false;
    }

    public function guess_base_url()
    {
        $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
        $base_url .= '://' . $_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $base_url = preg_replace('/install.*/', '', $base_url);

        return $base_url;
    }

    public function steps()
    {
        $step = $this->current_step;

        return [
            [
                'id'     => 1,
                'name'   => 'Requirements',
                'status' => $step > 1 ? 'complete' : 'current',
            ],
            [
                'id'     => 2,
                'name'   => 'Permissions',
                'status' => $step < 2 ? 'upcoming' : ($step > 2 ? 'complete' : 'current'),
            ],
            [
                'id'     => 3,
                'name'   => 'Database',
                'status' => $step < 3 ? 'upcoming' : ($step > 3 ? 'complete' : 'current'),
            ],
            [
                'id'     => 4,
                'name'   => 'Install',
                'status' => $step < 4 ? 'upcoming' : ($step > 4 ? 'complete' : 'current'),
            ],
            [
                'id'     => 5,
                'name'   => 'Finish',
                'status' => $step === 5 ? 'complete' : 'upcoming',
            ],
        ];
    }

    public function get_timezones_list()
    {
        return [
            'EUROPE'     => DateTimeZone::listIdentifiers(DateTimeZone::EUROPE),
            'AMERICA'    => DateTimeZone::listIdentifiers(DateTimeZone::AMERICA),
            'INDIAN'     => DateTimeZone::listIdentifiers(DateTimeZone::INDIAN),
            'AUSTRALIA'  => DateTimeZone::listIdentifiers(DateTimeZone::AUSTRALIA),
            'ASIA'       => DateTimeZone::listIdentifiers(DateTimeZone::ASIA),
            'AFRICA'     => DateTimeZone::listIdentifiers(DateTimeZone::AFRICA),
            'ANTARCTICA' => DateTimeZone::listIdentifiers(DateTimeZone::ANTARCTICA),
            'ARCTIC'     => DateTimeZone::listIdentifiers(DateTimeZone::ARCTIC),
            'ATLANTIC'   => DateTimeZone::listIdentifiers(DateTimeZone::ATLANTIC),
            'PACIFIC'    => DateTimeZone::listIdentifiers(DateTimeZone::PACIFIC),
            'UTC'        => DateTimeZone::listIdentifiers(DateTimeZone::UTC),
        ];
    }
}