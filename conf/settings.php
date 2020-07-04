<?php

/**
 * Whiterabbit app settings file
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 *
 */
//=================== APPLICATION  SETTINGS ========================
define('APP_PATH', dirname(__DIR__));
define('APP_URL', "http://whiterabbit.in");     // Application theme url.
define('THEME_URL', "http://whiterabbit.in/ui");     // Application theme url.
define('THEME_STYLE_URL', THEME_URL . '/styles');

// =================== DATABASE CONFIGURATIONS ========================
define('WR_MASTER_DB_NAME', "whiterabbit");
define('WR_MASTER_DB_HOST', "localhost");
define('WR_MASTER_DB_USER', "root");
define('WR_MASTER_DB_PASSWORD', "dezire");

define('WR_SLAVE_DB_NAME', "whiterabbit");
define('WR_SLAVE_DB_HOST', "localhost");
define('WR_SLAVE_DB_USER', "root");
define('WR_SLAVE_DB_PASSWORD', "dezire");

define('MANAGE_MAX_ROWS', 50);

/**
 * stop
 *
 * @param int $var Mixed var
 *
 * @return  ''
 *
 */
function stop($var) {
    pre($var);
    exit;
}

/**
 * pre
 *
 * @param int $var Mixed var
 *
 * @return  ''
 *
 */
function pre($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}
