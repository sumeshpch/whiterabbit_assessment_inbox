
<?php

//ini_set('display_errors',1);
include('conf/settings.php');
include(APP_PATH . '/conf/initCron.php');
include(APP_PATH . '/controllers/Crons.php');

$cronobj = new \app\controllers\Crons();
$cronobj->fetchAllEmails();
?>
