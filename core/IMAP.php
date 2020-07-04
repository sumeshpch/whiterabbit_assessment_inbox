<?php

namespace app\core;

/**
  IMAP
 */
class IMAP {

    public function __construct() {
        try {
            if (function_exists('imap_open')) {
                /* Connecting Gmail server with IMAP */
                $this->connection = imap_open('{imap.gmail.com:993/imap/ssl}INBOX', 'harveyspect60@gmail.com', 'zwukybktspqnzwqk') or die('Cannot connect to Gmail: ' . imap_last_error());
            }
        } catch (Exception $e) {
            die('Cannot connect to Gmail');
        }
    }

    public function retrieveAll() {
        $emails = imap_search($this->connection, 'ALL');

        if ($emails)
            rsort($emails);

        return $emails;
    }

    public function setAsSeen($email_number) {
        return imap_setflag_full($this->connection, $email_number, "\\Seen");
    }

    public function retrieveUnseen() {
        $emails = imap_search($this->connection, 'UNSEEN');

        if ($emails)
            rsort($emails);

        return $emails;
    }

    public function fetchOverview($email_number) {
        return imap_fetch_overview($this->connection, $email_number, 0);
    }

    public function fetchBody($email_number) {
        return imap_fetchbody($this->connection, $email_number, 2);
    }

    public function deleteEmail($email_number) {
        return imap_delete($this->connection, $email_number);
    }

    public function __destruct() {
        /* close the connection */
        imap_close($this->connection);
    }

}
