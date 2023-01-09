<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Session_database_driver extends CI_Session_driver implements SessionHandlerInterface
{
    /**
     * DB object
     *
     * @var	object
     */
    protected $_db;

    /**
     * Row exists flag
     *
     * @var	bool
     */
    protected $_row_exists = false;

    /**
     * Lock "driver" flag
     *
     * @var	string
     */
    protected $_platform;

    // ------------------------------------------------------------------------

    /**
     * Class constructor
     *
     * @param	array	$params	Configuration parameters
     * @return	void
     */
    public function __construct(&$params)
    {
        parent::__construct($params);

        $CI = & get_instance();
        isset($CI->db) or $CI->load->database();
        $this->_db = $CI->db;

        if (! $this->_db instanceof CI_DB_query_builder) {
            throw new Exception('Query Builder not enabled for the configured database. Aborting.');
        } elseif ($this->_db->pconnect) {
            throw new Exception('Configured database connection is persistent. Aborting.');
        } elseif ($this->_db->cache_on) {
            throw new Exception('Configured database connection has cache enabled. Aborting.');
        }

        $db_driver = $this->_db->dbdriver . (empty($this->_db->subdriver) ? '' : '_' . $this->_db->subdriver);
        if (strpos($db_driver, 'mysql') !== false) {
            $this->_platform = 'mysql';
        } elseif (in_array($db_driver, ['postgre', 'pdo_pgsql'], true)) {
            $this->_platform = 'postgre';
        }

        // Note: BC work-around for the old 'sess_table_name' setting, should be removed in the future.
        if (! isset($this->_config['save_path']) && ($this->_config['save_path'] = config_item('sess_table_name'))) {
            log_message('debug', 'Session: "sess_save_path" is empty; using BC fallback to "sess_table_name".');
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Open
     *
     * Initializes the database connection
     *
     * @param	string	$save_path	Table name
     * @param	string	$name		Session cookie name, unused
     * @return	bool
     */
    #[\ReturnTypeWillChange]
    public function open($save_path, $name)
    {
        if (empty($this->_db->conn_id) && ! $this->_db->db_connect()) {
            return $this->_failure;
        }

        $this->php5_validate_id();

        return $this->_success;
    }

    // ------------------------------------------------------------------------

    /**
     * Read
     *
     * Reads session data and acquires a lock
     *
     * @param	string	$session_id	Session ID
     * @return	string	Serialized session data
     */
    #[\ReturnTypeWillChange]
    public function read($session_id)
    {
        if ($this->_get_lock($session_id) === false) {
            return $this->_failure;
        }

        // Prevent previous QB calls from messing with our queries
        $this->_db->reset_query();

        // Needed by write() to detect session_regenerate_id() calls
        $this->_session_id = $session_id;

        $this->_db
            ->select('data')
            ->from($this->_config['save_path'])
            ->where('id', $session_id);

        if ($this->_config['match_ip']) {
            $this->_db->where('ip_address', $_SERVER['REMOTE_ADDR']);
        }

        if (! ($result = $this->_db->get()) or ($result = $result->row()) === null) {
            // PHP7 will reuse the same SessionHandler object after
            // ID regeneration, so we need to explicitly set this to
            // FALSE instead of relying on the default ...
            $this->_row_exists  = false;
            $this->_fingerprint = md5('');

            return '';
        }

        // PostgreSQL's variant of a BLOB datatype is Bytea, which is a
        // PITA to work with, so we use base64-encoded data in a TEXT
        // field instead.
        $result = ($this->_platform === 'postgre')
            ? base64_decode(rtrim($result->data))
            : $result->data;

        $this->_fingerprint = md5($result);
        $this->_row_exists  = true;

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Write
     *
     * Writes (create / update) session data
     *
     * @param	string	$session_id	Session ID
     * @param	string	$session_data	Serialized session data
     * @return	bool
     *
     */
    #[\ReturnTypeWillChange]
    public function write($session_id, $session_data)
    {
        // Prevent previous QB calls from messing with our queries
        $this->_db->reset_query();

        // Was the ID regenerated?
        if (isset($this->_session_id) && $session_id !== $this->_session_id) {
            if (! $this->_release_lock() or ! $this->_get_lock($session_id)) {
                return $this->_failure;
            }

            $this->_row_exists = false;
            $this->_session_id = $session_id;
        } elseif ($this->_lock === false) {
            return $this->_failure;
        }

        if ($this->_row_exists === false) {
            $insert_data = [
                'id'         => $session_id,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'timestamp'  => time(),
                'data'       => ($this->_platform === 'postgre' ? base64_encode($session_data) : $session_data),
            ];

            if ($this->_db->insert($this->_config['save_path'], $insert_data)) {
                $this->_fingerprint = md5($session_data);
                $this->_row_exists  = true;

                return $this->_success;
            }

            return $this->_failure;
        }

        $this->_db->where('id', $session_id);
        if ($this->_config['match_ip']) {
            $this->_db->where('ip_address', $_SERVER['REMOTE_ADDR']);
        }

        $update_data = ['timestamp' => time()];
        if ($this->_fingerprint !== md5($session_data)) {
            $update_data['data'] = ($this->_platform === 'postgre')
                ? base64_encode($session_data)
                : $session_data;
        }

        if ($this->_db->update($this->_config['save_path'], $update_data)) {
            $this->_fingerprint = md5($session_data);

            return $this->_success;
        }

        return $this->_failure;
    }

    // ------------------------------------------------------------------------

    /**
     * Close
     *
     * Releases locks
     *
     * @return	bool
     */
    #[\ReturnTypeWillChange]
    public function close()
    {
        return ($this->_lock && ! $this->_release_lock())
            ? $this->_failure
            : $this->_success;
    }

    // ------------------------------------------------------------------------

    /**
     * Destroy
     *
     * Destroys the current session.
     *
     * @param	string	$session_id	Session ID
     * @return	bool
     */
    #[\ReturnTypeWillChange]
    public function destroy($session_id)
    {
        if ($this->_lock) {
            // Prevent previous QB calls from messing with our queries
            $this->_db->reset_query();

            $this->_db->where('id', $session_id);
            if ($this->_config['match_ip']) {
                $this->_db->where('ip_address', $_SERVER['REMOTE_ADDR']);
            }

            if (! $this->_db->delete($this->_config['save_path'])) {
                return $this->_failure;
            }
        }

        if ($this->close() === $this->_success) {
            $this->_cookie_destroy();

            return $this->_success;
        }

        return $this->_failure;
    }

    // ------------------------------------------------------------------------

    /**
     * Garbage Collector
     *
     * Deletes expired sessions
     *
     * @param	int 	$maxlifetime	Maximum lifetime of sessions
     * @return	bool
     */
    #[\ReturnTypeWillChange]
    public function gc($maxlifetime)
    {
        // Prevent previous QB calls from messing with our queries
        $this->_db->reset_query();

        return ($this->_db->delete($this->_config['save_path'], 'timestamp < ' . (time() - $maxlifetime)))
            ? $this->_success
            : $this->_failure;
    }

    // --------------------------------------------------------------------

    /**
     * Validate ID
     *
     * Checks whether a session ID record exists server-side,
     * to enforce session.use_strict_mode.
     *
     * @param	string	$id
     * @return	bool
     */
    public function validateSessionId($id)
    {
        // Prevent previous QB calls from messing with our queries
        $this->_db->reset_query();

        $this->_db->select('1')->from($this->_config['save_path'])->where('id', $id);
        empty($this->_config['match_ip']) or $this->_db->where('ip_address', $_SERVER['REMOTE_ADDR']);
        $result                   = $this->_db->get();
        empty($result) or $result = $result->row();

        return ! empty($result);
    }

    // ------------------------------------------------------------------------

    /**
     * Get lock
     *
     * Acquires a lock, depending on the underlying platform.
     *
     * @param	string	$session_id	Session ID
     * @return	bool
     */
    protected function _get_lock($session_id)
    {
        if ($this->_platform === 'mysql') {
            $arg = md5($session_id . ($this->_config['match_ip'] ? '_' . $_SERVER['REMOTE_ADDR'] : ''));
            if ($this->_db->query("SELECT GET_LOCK('" . $arg . "', 300) AS ci_session_lock")->row()->ci_session_lock) {
                $this->_lock = $arg;

                return true;
            }

            return false;
        } elseif ($this->_platform === 'postgre') {
            $arg = "hashtext('" . $session_id . "')" . ($this->_config['match_ip'] ? ", hashtext('" . $_SERVER['REMOTE_ADDR'] . "')" : '');
            if ($this->_db->simple_query('SELECT pg_advisory_lock(' . $arg . ')')) {
                $this->_lock = $arg;

                return true;
            }

            return false;
        }

        return parent::_get_lock($session_id);
    }

    // ------------------------------------------------------------------------

    /**
     * Release lock
     *
     * Releases a previously acquired lock
     *
     * @return	bool
     */
    protected function _release_lock()
    {
        if (! $this->_lock) {
            return true;
        }

        if ($this->_platform === 'mysql') {
            if ($this->_db->query("SELECT RELEASE_LOCK('" . $this->_lock . "') AS ci_session_lock")->row()->ci_session_lock) {
                $this->_lock = false;

                return true;
            }

            return false;
        } elseif ($this->_platform === 'postgre') {
            if ($this->_db->simple_query('SELECT pg_advisory_unlock(' . $this->_lock . ')')) {
                $this->_lock = false;

                return true;
            }

            return false;
        }

        return parent::_release_lock();
    }
}
