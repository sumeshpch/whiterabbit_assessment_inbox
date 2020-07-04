<?php
/**
* 
* Dependency Injection container to hold services and variables
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
*/
namespace app\core;

/**
 * Container class implements the dependency injection container
 *
 * @author mathew <mathew@vtc.com>
 */
class Container
{
    /**
    *
    */
    protected static $values = array();

    /**
     * Sets the value in the container
     * 
     * @param int $id    name of the variable
     * @param int $value value of the variable
     *
     * @return void
     */
    public static function set($id, $value)
    {
        static::$values[$id] = $value;
    }

    /**
     * Returns the value set in the container
     * 
     * @param int $id name of the variable
     *
     * @return mixed returns the value
     */
    public static function get($id)
    {
        if (!isset(static::$values[$id])) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }
        return static::$values[$id];
    }

    /**
     * Sets the object of the service  to the dependency injection container
     * 
     * @param str   $service name of the service object
     * @param str   $class   name of the class that defines the service
     * @param array $params  parameters used by the service
     *
     * @return void
     */

    public static function setService($service, $class, $params=array())
    {
        if (isset(static::$values[$service])) {
            throw new \InvalidArgumentException(sprintf('Service "%s" already defined.', $service));
        }
        static::set(
            $service,
            function() use ($class, $params) {
            return new $class($params);
            }
        );
    }

    /**
     * Sets the object of the service as a shared service to the dependency injection container
     * 
     * @param str   $service name of the service object
     * @param str   $class   name of the class that defines the service
     * @param array $params  parameters used by the service
     *
     * @return void
     */
    public static function setSharedService($service, $class, $params=array())
    {
        if (isset(static::$values[$service])) {
            throw new \InvalidArgumentException(sprintf('Service "%s" already defined.', $service));
        }
        static::set(
            $service,
            function() use ($class, $params){
            static $object;
            if ($object == null) {
                $object = new $class($params);
            }
            return $object;
            }
        );
    }



    /**
     * Returns the object of the service
     *
     * @param str $service name of the service object
     *
     * @return object returns the service object 
     */
    public static function getService($service)
    {
        if (!isset(static::$values[$service])) {
            throw new \InvalidArgumentException(sprintf('Service "%s" is not defined.', $service));
        }
        $fn = static::$values[$service];
        return $fn();
    }

}
