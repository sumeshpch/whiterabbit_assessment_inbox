<?php

namespace app\controllers;

require_once APP_PATH . '/models/Crons.php';

use app\core\WRException;

class Crons extends \app\core\CronController {

    public function fetchAllEmails() {
        try {
            $cron = new \app\models\Crons();
            $data = $cron->fetchAllEmails();
            if ($data) {
                echo "success\n";
            } else {
                echo "failure\n";
            }
        } catch (WRException $e) {
            $this->handleException($e);
        }
    }

    public function fetchUnseenEmails() {
        try {
            $cron = new \app\models\Crons();
            $data = $cron->fetchUnseenEmails();
            if ($data) {
                echo "success\n";
            } else {
                echo "failure\n";
            }
        } catch (WRException $e) {
            $this->handleException($e);
        }
    }

}
