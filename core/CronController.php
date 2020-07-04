<?php

/**
 * Controller base class. All controller classes would extend this.
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

class CronController {

    /**
     * The request object
     * @var object
     */
    private $request;

    /**
     * The reponse object
     * @var object
     */
    private $response;

    /**
     * view template 
     */
    protected $view;

    /*
     *  array to store variables to be used in view
     */
    protected $data = array();

    protected function setData($key, $value) {
        $this->data[$key] = $value;
    }

    protected function setVars($vars) {
        if (!is_array($vars)) {
            return false;
        }
        $this->data = array_merge($this->data, $vars);
    }

    protected function redirect($url) {
        //bug, Header may not contain more than a single header, new line detected, below line may solve it
        // $url = ereg_replace( ' +', '%20', $url);
        header("Location: $url");
        exit;
    }

}
