<?php

namespace app\models;

use app\core\Session;
use app\core\Container;

class Users extends \app\core\Model {

    public static function getUserId() {
        return "123";

        /* $userId = Session::read('WHITERABBIT');
          return $userId; */
    }

}
