<?php

/**
 *
 * Request class models the data of the incoming HTTP request
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

/**
 * Request class
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */
class Request {

    /**
     * Body data of incoming request
     * @var str
     */
    private $_data;

    /**
     * HTTP request method of incoming request
     * @var str
     */
    private $_method;

    /**
     * Constructor - Create a request object processing the http headers.
     *
     */
    function __construct() {

        $this->_processRequest();
    }

    /**
     * Processes the request
     *
     * @return void
     */
    private function _processRequest() {
        // get HTTP method
        $method = strtoupper($this->getServerValue('REQUEST_METHOD'));

        $data = '';
        switch ($method) {
            case 'GET':
                $data = $_GET;
                break;

            case 'POST':
                $data = $_POST;
                break;

            default:
                // We handle only GET  AND POST
                break;
        }

        $this->setMethod($method);
        $this->setData($data);
    }

    /**
     * Fetches the value from $_SERVER
     *
     * @param str $serverVar server value
     *
     * @return void
     */
    public function getServerValue($serverVar) {
        if (isset($_SERVER[$serverVar]) && $_SERVER[$serverVar] != '') {
            return filter_input(INPUT_SERVER, $serverVar, FILTER_SANITIZE_STRING);
        } else {
            return false;
        }
    }

    /**
     * Sets the data
     *
     * @param mixed $data data
     *
     * @return void
     */
    public function setData($data) {
        $this->_data = $data;
    }

    /**
     * Fetches the data
     *
     * @return data
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * Sets the value for key in data
     *
     * @param mixed $data data
     *
     * @return void
     */
    public function setKey($key, $val) {
        $this->_data[$key] = $val;
    }

    /**
     * Returns the filtered data
     *
     * @return returns the filtered data
     */
    public function getFilteredData() {
        return $this->_filterData($this->_data);
    }

    /**
     * Filters the input data
     *
     * @param mixed $data Data
     *
     * @return data after filtering
     */
    private function _filterData($data) {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $val) {
                $data[$this->_filterData($key)] = $this->_filterData($val);
            }
        } else {
            $data = htmlspecialchars(trim($data), ENT_QUOTES, "UTF-8");
        }
        return $data;
    }

    /**
     * Sets the request method
     *
     * @param str $method request method
     *
     * @return void
     */
    public function setMethod($method) {
        $this->_method = $method;
    }

    /**
     * Fetches the method
     *
     * @return void
     */
    public function getMethod() {
        return $this->_method;
    }

}
