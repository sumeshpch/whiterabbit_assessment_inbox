<?php

/**
 * Database handler Class
 * 
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

mysqli_report(MYSQLI_REPORT_OFF);
mysqli_report(MYSQLI_REPORT_STRICT);

/**
 * DB Class
 *
 * @author mathew <mathew@vtc.com>
 */
class DB {

    /**
     * The db resource handle  
     */
    private $_handle;
    private $_dbHost;
    private $_dbUser;
    private $_dbPassword;
    private $_dbName;
    private $_mode;

    /**
     * Constructor -  sets the parameters and calls connect function
     *
     * @param string $params parameters array
     *
     * @return void
     */
    public function __construct($params = array()) {
        $this->_dbHost = $params['dbHost'];
        $this->_dbUser = $params['dbUser'];
        $this->_dbPassword = $params['dbPassword'];
        $this->_dbName = $params['dbName'];
        $this->_mode = ($params['mode'] == 'master') ? 'master' : 'slave';
        $this->connect();
    }

    /**
     * Sets up the connection to the database
     * 
     * @return void    
     */
    public function connect() {
        try {
            $this->_handle = new \mysqli($this->_dbHost, $this->_dbUser, $this->_dbPassword, $this->_dbName);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->_handle->set_charset('utf8');
    }

    /**
     * Queries the database.
     * Returns TRUE on success or FALSE on failure. For SELECT, SHOW etc a DbResult object will be returned. 
     * 
     * @param str $sql The SQL statement
     * 
     * @return DbResult object or true based on the query type
     */
    public function query($sql) {
        $result = $this->_handle->query($sql);

        if (is_object($result)) {
            return new DbResult($result);
        } elseif (!$result) {
            throw new \Exception($this->formatError($sql));
        } else {
            return true;
        }
    }

    /**
     * Executes the sql query
     *
     * @param string $sql Insert/Update/Delete query.
     * 
     * @return status
     */
    public function execute($sql) {
        if ($this->_mode == 'slave') {
            throw new \Exception('Illegal operation on slave database');
        }
        if (!$this->_handle->query($sql)) {
            throw new \Exception($this->formatError($sql));
        }
        return true;
    }

    /**
     * Returns the mysql insert id
     *
     * @return The mysqli insert id
     */
    public function getInsertId() {
        if (!$this->_handle) {
            return false;
        }
        return $this->_handle->insert_id;
    }

    /**
     * Returns the Number of rows affected in the last query execution
     *
     * @return Number of affected rows
     */
    public function getAffectedRows() {
        if (!$this->_handle) {
            return false;
        }
        return $this->_handle->affected_rows;
    }

    /**
     * Return mysql safe string
     *
     * @param str $string String to be escaped
     *
     * @return results of real_escape_string
     */
    function escape($string) {
        return $this->_handle->real_escape_string($string);
    }

    /**
     * Destructor calls close {@link close()}
     *
     * @return void
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * Closes the connection 
     *
     * @return void
     */
    public function close() {
        if ($this->_handle) {
            $this->_handle->close();
            $this->_handle = "";
        }
        return true;
    }

    /**
     * Prepares the SQL query and returns a DbStatement object 
     * 
     * @param str $query The SQL statement
     * 
     * @return void
     */
    public function prepare($query) {
        if (!$this->_handle) {
            return false;
        }
        return $this->_handle->prepare($query);
    }

    /**
     * Selects the database
     *
     * @param str $dbName database name
     *
     * @return void
     */
    public function selectDB($dbName) {
        if (!$this->_handle) {
            throw new \Exception('No db handle available, Cannot select db' . $dbName);
        }
        if (!$this->_handle->select_db($dbName)) {
            throw new \Exception('Cannot select db' . $dbName);
        }
    }

    /**
     * Format error for logging
     *
     * @param string $query query to be logged
     * 
     * @return void
     */
    function formatError($query) {
        $time = date("Y-m-d H:i:s");
        $string = "---------------$time----" . $_SERVER['REMOTE_ADDR'] . "----------------------------------\r\n";
        $query = str_replace("\r", " ", $query);
        $query = str_replace("\n", " ", $query);
        $query = preg_replace('/\s\s+/', ' ', $query);
        $string .= "Query :::: $query \r\n";
        $string .= "Page :::: $_SERVER[PHP_SELF] \r\n----------------------------------------------------\r\n\r\n";
        return $string;
    }

    /**
     * Starts a transaction
     * 
     * @return void
     */
    public function startTransaction() {
        $this->_handle->autocommit(false);
    }

    /**
     * Commits a transaction
     * 
     * @return void
     */
    public function commit() {
        $this->_handle->commit();
        $this->_handle->autocommit(true);   //so that non transactional queries will be executed
    }

    /**
     * Rollsback a transaction
     * 
     * @return void
     */
    public function rollback() {
        $this->_handle->rollback();
    }

    /**
     * Returns the total rows
     * 
     * @return total rows
     */
    public function getTotalRows() {
        return $this->_handle->query("SELECT FOUND_ROWS()")->fetch_row();
    }

}

/**
 * Database Result Class
 *
 * @author mathew <mathew@vtc.com>
 */
class DbResult {

    /**
     * The mysqli result object
     */
    private $_result;

    /**
     * Array to store rows in the result
     */
    private $_resultArray = array();

    /**
     * Array to store fields in a row
     */
    private $_fieldArray = array();

    /**
     * Shows whether $resultArray is populated or not
     */
    private $_fetchFlag = false;

    /**
     * Constructor sets variable $result {@link $result}
     *
     * @param link $result result pointer
     *
     * @return void
     */
    public function __construct($result) {
        $this->_result = $result;
    }

    /**
     * Returns the value of a specific field of the current row
     *
     * @param str $fieldName field name
     * 
     * @return Value Of a field
     */
    public function fields($fieldName) {
        if (!$this->_result) {
            return false;
        }
        $this->_fieldArray = $this->_fieldArray ? $this->_fieldArray : $this->_result->fetch_assoc();
        return $this->_fieldArray[$fieldName];
    }

    /**
     * Returns an array that corresponds to the fetched row or NULL if there are 
     * no more rows for the resultset represented by the result parameter
     * 
     * @return Array An array corresponding to the fetched row
     */
    public function fetch() {
        if (!$this->_result) {
            return false;
        }
        return $this->_result->fetch_assoc();
    }

    /**
     * Returns a array that corresponds to the fetched row or NULL if there are 
     * no more rows for the resultset represented by the result parameter
     * 
     * @return Array An array corresponding to the fetched row
     */
    public function fetchRow() {
        if (!$this->_result) {
            return false;
        }
        return $this->_result->fetch_row();
    }

    /**
     * Returns an array that corresponds to the fetched row or NULL if there are 
     * no more rows for the resultset represented by the result parameter
     * 
     * @return Array An array corresponding to the fetched row
     */
    public function fetchArray() {
        if (!$this->_result) {
            return false;
        }
        return $this->_result->fetch_array();
    }

    /**
     * Returns the number of rows in the result set
     * 
     * @return the number of rows in the result set
     */
    public function getCount() {
        if (!$this->_result) {
            return false;
        }
        return $this->_result->num_rows;
    }

    /**
     * Returns the number of rows in the result set
     * 
     * @return the number of rows in the result set
     */
    public function eOf() {
        if (!$this->_result) {
            return true;
        }
        if ($this->_result->num_rows == $this->_result->current_field) {
            return true;
        }
        return false;
    }

    /**
     * Fetch all rows of the result as an array and return it
     * 
     * @return array return all the results in an array
     */
    public function fetchAll() {
        if (!$this->_fetchFlag) {
            while ($row = $this->fetch()) {
                $this->_resultArray[] = $row;
            }
            $this->_fetchFlag = true;
        }
        return $this->_resultArray;
    }

    /**
     * seeks to a result pointer specified by the offset
     * 
     * @param int $offset result pointer offset
     *
     * @return void
     */
    public function seek($offset = "0") {
        $this->_result->data_seek($offset);
    }

    /**
     * Closes the resultSet 
     * 
     * @return void
     */
    public function close() {
        if (!$this->_result) {
            return false;
        }
        $this->_result->close();
    }

}
