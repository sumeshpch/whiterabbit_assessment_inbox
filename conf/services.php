<?php

/**
 * This  file contains all services configuration in the application
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */
use app\core\Container;

include(APP_PATH . '/core/Logger.php');
include(APP_PATH . '/core/DB.php');
include(APP_PATH . '/core/WRException.php');
include(APP_PATH . '/core/IMAP.php');

$dbWriterParams = array('dbName' => WR_MASTER_DB_NAME,
    'dbHost' => WR_MASTER_DB_HOST,
    'dbUser' => WR_MASTER_DB_USER,
    'dbPassword' => WR_MASTER_DB_PASSWORD,
    'mode' => 'master');
Container::setSharedService('dbWriter', 'app\\core\\DB', $dbWriterParams);

$dbReaderParams = array('dbName' => WR_SLAVE_DB_NAME,
    'dbHost' => WR_SLAVE_DB_HOST,
    'dbUser' => WR_SLAVE_DB_USER,
    'dbPassword' => WR_SLAVE_DB_PASSWORD,
    'mode' => 'slave');
Container::setSharedService('dbReader', 'app\\core\\DB', $dbReaderParams);

Container::setSharedService('Logger', 'app\\core\\Logger');
Container::setSharedService('imap', 'app\\core\\IMAP');

date_default_timezone_set('Asia/Calcutta');
