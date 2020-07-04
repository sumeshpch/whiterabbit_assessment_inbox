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

        $emails = $imap->retrieveAll();
        if (count($emails) > 0) {

            $mails = [];
            $query = "INSERT INTO `emails`(`mailId`, `subject`, `body`, `status`, `from`, `to`, `cc`, `bcc`, `date`) VALUES ";

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
        }
        return true;
    }

    public function fetchUnseenEmails() {
        $imap = Container::getService('imap');
        $dbWriter = Container::getService('dbWriter');
        $logger = Container::getService('Logger');

        $message = "Fetch latest emails job started\n";
        $logger->info($message);
        echo "\n";

        $emails = $imap->retrieveUnseen();
        if (count($emails) > 0) {

            $mails = [];
            $query = "INSERT INTO `emails`(`mailId`, `subject`, `body`, `status`, `from`, `to`, `cc`, `bcc`, `date`) VALUES ";

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
        return true;
    }

}
