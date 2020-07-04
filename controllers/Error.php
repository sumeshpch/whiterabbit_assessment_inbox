<?php

/**
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\controllers;

class Error extends \app\core\Controller {

    function showError() {
        $data = $this->getRequest()->getFilteredData();
        $this->setData('errMessage', stripslashes($data['msg']));
        $this->view = APP_PATH . '/views/error/error.tpl.php';
        $this->render();
    }

    function noAccess() {
        $this->view = APP_PATH . '/views/error/noAccess.tpl.php';
        $this->render();
    }

}
