<?php

/**
 *
 * ErrorHandler classs
 *
 * The `ErrorHandler` class allows PHP errors and exceptions to be handled in a uniform way.
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

/**
 * ErrorHandler
 *
 * @author sajith <sajith@hifx.co.in>
 */
class ErrorHandler {

    /**
     * Custom  error handler function to log errors
     *
     * @param int $errNumber  Error number
     * @param str $errMessage Error message
     * @param str $file       File
     * @param int $line       Line number
     * @param str $context    Context
     *
     * @return void
     */
    public static function handleError($errNumber, $errMessage, $file, $line, $context) {
        $error = "$file:$line  $errMessage";
        $logger = Container::getService('Logger');
        switch ($errNumber) {
            case E_NOTICE:
            case E_USER_NOTICE:
                //NOTICE
                $logger->notice($error);
                return true;
                break;

            case E_WARNING:
            case E_CORE_WARNING;
            case E_USER_WARNING;
                //WARN
                $logger->warn($error);
                return true;
                break;

            case E_ERROR:
            case E_USER_ERROR:
            case E_CORE_ERROR:
            case E_RECOVERABLE_ERROR:
                //ERR
                $logger->error($error);
                break;

            default:
                $logger->debug($error);
                break;
            //INFO
        }
        //Send error response
        self::sendError();
        exit;
    }

    /**
     * Custom  exception handling function to log exceptions
     *
     * @param Exception $exception Exception
     *
     * @return void
     */
    public static function handleException($exception) {
        $file = $exception->getFile();
        $line = $exception->getLine();
        $errMessage = $exception->getMessage();
        $trace = $exception->getTraceAsString();
        $error = "$file:$line  $errMessage $trace";
        $logger = Container::getService('Logger');
        $logger->excpt($error);
        self::sendError($errMessage);
        exit;
    }

    /**
     * Send 500 response code
     *
     * @return void
     */
    public static function sendError($errMessage = "") {
        //TODO: send 404
        $responseObj = new Response();
        $body = array();
        $body['error']['code'] = 400;
        $body['error']['type'] = "error_unknown";
        $body['error']['message'] = $errMessage;
        $responseObj->setContent(json_encode($body));
        $responseObj->setStatus('400');
        $responseObj->setHeader('Content-Type: application/json');

        $responseObj->sendResponse();
    }

    /**
     * init sets the error/excpetion handlers 
     *
     * @return void
     */
    public static function init() {
        set_error_handler(array("app\core\ErrorHandler", "handleError"));
        set_exception_handler(array("app\core\ErrorHandler", "handleException"));
    }

}
