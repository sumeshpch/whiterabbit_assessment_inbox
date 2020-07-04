<?php

/**
 *
 * Base Model class. All app models would extend this.

 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

/**
 * Base Model class. All app models would extend this.
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */
class Model {

    /**
     * The request object
     * @var object
     */
    private $_request;

    /**
     * array to store errors
     *
     */
    protected $errors = array();

    /**
     * to store error message
     *
     */
    protected $errorMesssage;

    /**
     * Class constructor. Initializes class and
     * assigns the request and response object
     *
     * @param array $config configuration
     *
     * @return void
     */
    function __construct(array $config = array()) {
        if (isset($config['request'])) {
            $this->setRequest($config['request']);
        }
    }

    /**
     * Sets the request object
     *
     * @param object $request The request object
     *
     * @return void
     */
    public function setRequest(Request $request) {
        $this->_request = $request;
    }

    /**
     * Returns the request object
     *
     * @return void
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * Returns the error Array
     *
     * @return Array
     */
    function getError() {
        return $this->errors;
    }

    /**
     * Sets the error
     *
     * @param string $field Field
     *
     * @param string $error Error
     *
     * @return String
     *
     */
    function setError($field, $error) {
        $this->errors[$field] = $error;
    }

    /**
     * Returns the error message
     *
     * @return String
     *
     */
    function getErrorMessage() {
        return $this->errorMesssage;
    }

    /**
     * Sets the error message
     *
     * @param string $error Error message
     *
     * @return String
     *
     */
    function setErrorMessage($error) {
        $this->errorMesssage .= "$error";
    }

    /**
     * parse the error message
     *
     * @param array $error Error message
     *
     * @return String
     *
     */
    public function parseErrorMessage() {
        if ($this->errors) {
            foreach ($this->errors as $key => $value) {
                $this->errMsg .= $value . "<br/>";
            }
        }
    }

}
