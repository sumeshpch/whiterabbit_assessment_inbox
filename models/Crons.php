<?php

namespace app\models;

use app\core\Container;

class Crons extends \app\core\Model {

    public function fetchAllEmails() {
        $imap = Container::getService('imap');
        $dbWriter = Container::getService('dbWriter');
        $logger = Container::getService('Logger');

        $message = "Fetch all emails job started\n";
        $logger->info($message);
        echo "\n";

        $truncateQuery = "TRUNCATE `emails`";
        $dbWriter->execute($truncateQuery);

        $count = 0;
        $emails = $imap->retrieveAll();
        if (is_array($emails)) {

            $count = count($emails);

            $mails = [];
            $query = "INSERT INTO `emails`(`mailId`, `subject`, `body`, `status`, `from`, `to`, `date`) VALUES ";

            foreach ($emails as $email_number) {
                $imap->setAsSeen($email_number);

                $overview = $imap->fetchOverview($email_number);

                if (!$overview[0]->deleted) {
                    $subject = $overview[0]->subject;
                    $body = $imap->fetchBody($email_number);
                    $status = ($overview[0]->seen) ? "read" : "unread";
                    $from = $overview[0]->from;
                    $to = $overview[0]->to;
                    /* $cc = $overview[0]->cc;
                      $bcc = $overview[0]->bcc; */
                    $date = date("Y-m-d H:i:s", strtotime($overview[0]->date));

                    array_push($mails, '("' . $email_number . '", "' . $subject . '", "' . $dbWriter->escape($body) . '", "' . $status . '", "' . $from . '", "' . $to . '", "' . $date . '")');
                }
            }

            $query .= implode(",", $mails);
            $dbWriter->execute($query);

            //log activity
            $dbWriter->execute("INSERT INTO `system_activity` (`updateCount`) VALUES ('" . $count . "')");
        }
        $dbWriter->close();
        return true;
    }

    public function fetchUnseenEmails() {
        $imap = Container::getService('imap');
        $dbWriter = Container::getService('dbWriter');
        $logger = Container::getService('Logger');

        $message = "Fetch latest emails job started\n";
        $logger->info($message);
        echo "\n";

        $count = 0;
        $emails = $imap->retrieveUnseen();
        if (is_array($emails)) {

            $count = count($emails);

            $query = "INSERT INTO `emails`(`mailId`, `subject`, `body`, `status`, `from`, `to`, `date`) VALUES ";

            foreach ($emails as $email_number) {
                $overview = $imap->fetchOverview($email_number);

                if (!$overview[0]->deleted) {
                    $subject = $overview[0]->subject;
                    $body = $imap->fetchBody($email_number);
                    $status = ($overview[0]->seen) ? "read" : "unread";
                    $from = $overview[0]->from;
                    $to = $overview[0]->to;
                    /* $cc = $overview[0]->cc;
                      $bcc = $overview[0]->bcc; */
                    $date = date("Y-m-d H:i:s", strtotime($overview[0]->date));

                    $insertQuery = $query . '("' . $email_number . '", "' . $subject . '", "' . $dbWriter->escape($body) . '", '
                            . '"' . $status . '", "' . $from . '", "' . $to . '", "' . $date . '") '
                            . 'ON DUPLICATE KEY UPDATE `` = "' . $date . '"';
                    $dbWriter->execute($insertQuery);
                }
            }
        }
        //log activity
        $dbWriter->execute("INSERT INTO `system_activity` (`action`, `updateCount`) VALUES ('fetchLatest', '" . $count . "')");
        $dbWriter->close();
        return true;
    }

}
