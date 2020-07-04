<?php

namespace app\controllers;

use app\core\WRException;

require_once APP_PATH . '/models/email.php';

class PublicManagement extends \app\core\Controller {

    public function listEmails() {
        $emailObj = new \app\models\Email(['request' => $this->getRequest()]);
        $data = $emailObj->listEmails();
        $this->setData('data', $data);

        $this->view = APP_PATH . '/views/email/list.tpl.php';
        $this->render();
    }

    public function getEmail($args) {
        $id = $args[1];
        $emailObj = new \app\models\Email(['request' => $this->getRequest()]);
        $email = $emailObj->getEmail($id);
        $this->setVars($email);

        $this->view = APP_PATH . '/views/email/read.tpl.php';
        $this->render();
    }

    public function deleteEmail() {
        $data = $this->getRequest()->getFilteredData();
        $emailObj = new \app\models\Email(['request' => $this->getRequest()]);
        if ($emailObj->deleteEmail()) {
            $this->redirect(APP_URL . "/inbox");
        } else {
            $this->redirect(APP_URL . "/email/" . $data['mailId']);
        }
    }

}
