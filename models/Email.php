<?php

namespace app\models;

use app\core\Container;

class Email extends \app\core\Model {

    public function listEmails() {
        $data = $this->getRequest()->getFilteredData();
        $dbReader = Container::getService('dbReader');

        $pageNo = (empty($data['page'])) ? "0" : $data['page'];
        $start = ($pageNo != '') ? ($pageNo) * MANAGE_MAX_ROWS : '0';

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
        }
        $rs->close();
        //pre($emails);exit;
        return $emails;
    }

    public function getEmail($mailId = 0) {
        $dbReader = Container::getService('dbReader');

        $query = "SELECT `mailId`, `subject`, `date`, `from`, `body` FROM `emails` WHERE `mailId` = '" . $mailId . "'";

        $rs = $dbReader->query($query);

        $email = [];
        $totalEmails = $rs->getCount();

        if ($totalEmails > 0) {
            $row = $rs->fetch();
            $email = $row;
        }
        $rs->close();

        return $email;
    }

    public function deleteEmail() {
        $data = $this->getRequest()->getFilteredData();
        $dbWriter = Container::getService('dbWriter');

        $query = "DELETE FROM `emails` WHERE `mailId` = '" . $data['mailId'] . "'";

        $imap = Container::getService('imap');
        $status = $imap->deleteEmail($data['mailId']);
        if ($status) {
            $dbWriter->execute($query);
            return true;
        }
        return false;
    }

}
