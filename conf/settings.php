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
define('THEME_URL', APP_URL . "/ui");     // Application theme url.
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

define("SMTP_EMAIL_USERNAME", "harveyspect60@gmail.com");
define("SMTP_EMAIL_PASSWORD", "zwukybktspqnzwqk");

define('MANAGE_MAX_ROWS', 10);
define('COOKIE_SECRET_KEY', "WHITERABBIT");
define('COOKIE_EXPIRY_TIME', "");
define('COOKIE_PATH', "");
define('COOKIE_DOMAIN', "");

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
