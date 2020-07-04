<?php

/**
 * This  file is the gateway to the front end. It is responsible for intercepting requests
 * instantiating appropriate classes and calling the execute function.
 *
 * @package whiterabbit
 * @author  sumesh <sumeshpch@gmail.com>
 * @version SVN: $Id$
 *
 */
//ini_set('display_errors',1);
//phpinfo();
require_once 'conf/settings.php';
require_once APP_PATH . '/core/Container.php';
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/conf/routes.php';     // Including the routes

require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/Request.php';
require_once APP_PATH . '/core/Response.php';

require_once APP_PATH . '/conf/services.php';   // Including the configs
require_once APP_PATH . '/core/ErrorHandler.php';
require_once APP_PATH . '/core/WRException.php';

require_once APP_PATH . '/core/Util.php';
require APP_PATH . '/core/InitHandler.php';

app\core\ErrorHandler::init();
date_default_timezone_set('Asia/Calcutta');
$requestObj = new app\core\Request(); //GET the request object
$responseObj = new app\core\Response(); //GET the response object
app\core\InitHandler::init();

try {
    $params = app\core\Router::parse();
    $class = $params['class'];
    $method = $params['action'];
    $matches = $params['matches'];

    include_once APP_PATH . "/controllers/$class.php";
    $namespacedClass = 'app\\controllers\\' . $class;
    if (class_exists($namespacedClass)) {
        $obj = new $namespacedClass(array('request' => $requestObj, 'response' => $responseObj));
        if (method_exists($obj, $method)) {
            app\core\Util::setMethod($class, $method);
            $obj->setDefaultView($class, $method);
            $obj->$method($matches);

            exit;
        } else {
            throw new BadMethodCallException("Method, $method, not supported.");
        }
    } else {
        throw new BadMethodCallException("Class, $class, not found.");
    }
} catch (BadMethodCallException $exception) {
    $responseObj->setStatus('404');
    $responseObj->sendResponse();
}
