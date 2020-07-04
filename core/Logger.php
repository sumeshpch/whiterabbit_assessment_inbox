<?php

/**
 *
 * Logger classs
 *
 * The `Logger` class allows you to log .
 *
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

/**
 * Logger class
 *
 * @author sajith <sajith@hifx.co.in>
 */
class Logger {

    const ALERT = 'ALERT';  // Alert: action must be taken immediately
    const ERR = 'ERR';  // Error: error conditions
    const WARN = 'WARN';  // Warning: warning conditions
    const NOTICE = 'NOTICE';  // Notice: normal but significant condition
    const INFO = 'INFO';  // Informational: informational messages
    const DEBUG = 'DEBUG';  // Debug: debug messages
    const EXCPT = 'EXCPT';  // Debug: debug messages

    /**
     * Logs the error as debug
     *
     * @param str $msg Message
     *
     * @return void
     */

    public static function debug($msg) {
        self::log(self::DEBUG, $msg);
    }

    /**
     * Logs the error as error
     *
     * @param str $msg Message
     *
     * @return void
     */
    public static function error($msg) {
        self::log(self::ERR, $msg);
    }

    /**
     * Logs the error as notice
     *
     * @param str $msg Message
     *
     * @return void
     */
    public static function notice($msg) {
        //self::log(self::NOTICE, $msg);
    }

    /**
     * Logs the message as info
     *
     * @param str $msg Message
     *
     * @return void
     */
    public static function info($msg) {
        self::log(self::INFO, $msg);
    }

    /**
     * Logs the error as warning
     *
     * @param str $msg Message
     *
     * @return void
     */
    public static function warn($msg) {
        self::log(self::WARN, $msg);
    }

    /**
     * Logs the error as alert
     *
     * @param str $msg Message
     *
     * @return void
     */
    public static function alert($msg) {
        self::log(self::ALERT, $msg);
    }

    /**
     * Logs the error as exception
     *
     * @param str $msg Message
     *
     * @return void
     */
    public static function excpt($msg) {
        self::log(self::EXCPT, $msg);
    }

    /**
     * Writes to apache error log
     *
     * @param str $type Type of error
     * @param str $msg  Message
     *
     * @return void
     */
    public static function log($type, $msg) {
        $msg = str_replace(PHP_EOL, " ", $msg);
        $msg = $type . '_START: ' . $msg . ' ' . $type . '_END' . PHP_EOL;

        $outHandler = fopen('php://stdout', 'w');
        fwrite($outHandler, $msg);
        fclose($outHandler);
    }

}
