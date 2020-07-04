<?php

namespace app\models;

use app\core\Container;

require_once APP_PATH . '/models/Users.php';

class Email extends \app\core\Model {

    public function listEmails() {
        $data = $this->getRequest()->getFilteredData();
        $dbReader = Container::getService('dbReader');
        $dbWriter = Container::getService('dbWriter');

        $userId = \app\models\Users::getUserId();

        $pageNo = (empty($data['page'])) ? 1 : $data['page'];
        $start = ($pageNo - 1) * MANAGE_MAX_ROWS;

        $where = "WHERE 1";
        if ($data['email']) {
            $where .= " AND `from` LIKE '%" . $data['email'] . "%'";
        }
        if ($data['search']) {
            $where .= " AND (`subject` LIKE '%" . $data['search'] . "%' OR `body` LIKE '%" . $data['search'] . "%')";
        }

        $countQuery = "SELECT COUNT(*) AS `count` FROM `emails`" . $where;
        $rsCount = $dbReader->query($countQuery);
        $rowCount = $rsCount->fetch();
        $this->totalPages = ceil($rowCount['count'] / MANAGE_MAX_ROWS);
        $rsCount->close();

        $query = "SELECT `mailId`, `subject`, `date`, `from` FROM `emails` " . $where . " ORDER BY `id` DESC LIMIT " . $start . "," . MANAGE_MAX_ROWS;

        $rs = $dbReader->query($query);

        $emails = [];
        $totalEmails = $rs->getCount();

        if ($totalEmails > 0) {
            while ($row = $rs->fetch()) {
                $emails[] = $row;
            }

            //log activity
            $dbWriter->execute("INSERT INTO `user_activity` (`userId`) VALUES ('" . $userId . "')");
        }
        $rs->close();
        $dbWriter->close();
        //pre($emails);exit;

        $this->pageNo = $pageNo;
        return $emails;
    }

    public function getEmail($mailId = 0) {
        $dbReader = Container::getService('dbReader');
        $dbWriter = Container::getService('dbWriter');

        $userId = \app\models\Users::getUserId();

        $query = "SELECT `mailId`, `subject`, `date`, `from`, `body` FROM `emails` WHERE `mailId` = '" . $mailId . "'";

        $rs = $dbReader->query($query);

        $email = [];
        $totalEmails = $rs->getCount();

        if ($totalEmails > 0) {
            $row = $rs->fetch();
            $email = $row;

            //log activity
            $dbWriter->execute("INSERT INTO `user_activity` (`mailId`, `userId`, `action`) VALUES ('" . $mailId . "', '" . $userId . "', 'read')");
        }
        $rs->close();
        $dbWriter->close();

        return $email;
    }

    public function deleteEmail() {
        $data = $this->getRequest()->getFilteredData();
        $dbWriter = Container::getService('dbWriter');

        $userId = \app\models\Users::getUserId();

        $query = "DELETE FROM `emails` WHERE `mailId` = '" . $data['mailId'] . "'";

        $imap = Container::getService('imap');
        $status = $imap->deleteEmail($data['mailId']);
        if ($status) {
            $dbWriter->execute($query);

            //log activity
            $dbWriter->execute("INSERT INTO `user_activity` (`mailId`, `userId`, `action`) VALUES ('" . $data['mailId'] . "', '" . $userId . "', 'delete')");

            return true;
        }
        $dbWriter->close();
        return false;
    }

}
