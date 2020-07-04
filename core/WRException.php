<?php

/**
 *
 * Base class for WR Excpetions
 *
 * @author sumesh <sumeshpch@gmail.com>
 */

namespace app\core;

/**
 * WRException class
 *
 * @author sumesh <sumeshpch@gmail.com>
 */
class WRException extends \Exception {

    public $ret = array();
    public $error_type = "invalid_request";

    public function __construct($message, $code = 0, $error_type = "", \Exception $previous = null) {
        if ($error_type != "") {
            $this->error_type = $error_type;
        }
        parent::__construct($message, $code, $previous);
    }

    public function returnError() {
        $this->ret = array(
            'error' => array(
                'code' => $this->getCode(),
                'type' => $this->error_type,
                'info' => $this->getMessage()
            )
        );
        return $this->ret;
    }

    public function code() {
        return $this->getCode();
    }

    public function message() {
        return _($this->getMessage());
    }

}
