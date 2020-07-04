<?php

/**
 *
 * Router class
 *
 * The primary responsibility of the `Router` class is  to determine the correct set of
 * class and method to execute for the incoming requests.
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

/**
 * Router class
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */
class Router {

    private static $_routes;

    /**
     * Adds a new route
     * The order in which routes are connected matters,
     * since the order of precedence is taken into account
     * in parsing and matching operations.
     *
     * @param string $pattern The incoming request pattern to match
     * @param array  $params  An array explaining containing the class and method to execute
     *
     * @return void
     */
    public static function add($pattern, $params) {
        self::$_routes[$pattern] = $params;
    }

    /**
     * Run function inspects the route
     * finds the class/method and runs it
     *
     * @throws  Exception   Thrown if corresponding class is not found
     * @throws  Exception   Thrown if no match is found
     *
     * @return array $params Returns the class name and method name to be executed
     */
    public static function parse() {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);   //Request method

        if ($method == "OPTIONS") {
            return [
                'class' => 'Admin',
                'action' => 'addPreflight'
            ];
        }

        $path = $_SERVER['REQUEST_URI'];    //Request path

        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }
        $found = false;

        foreach (self::$_routes as $pattern => $params) {
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '^' . $pattern . '\/?$';

            if (preg_match("/$pattern/i", $path, $matches)) {
                $found = true;

                if (!isset($params[$method])) {

                    throw new \BadMethodCallException("Method:$method, not supported.");
                }

                //Add the regex match params to return array

                $params[$method]['matches'] = $matches;
                return $params[$method];
            }
        }
        if (!$found) {
            throw new \UnexpectedValueException("Route not found.$path");
        }
    }

}
