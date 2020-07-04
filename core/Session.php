<?php

/**
 *
 * Session class
 *
 * The primary responsibility of the `Session` class is  to set the userId cookie
 * and read it back.
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

/**
 * Session class
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */
class Session {

    /**
     * Reads the cookie
     *
     * @param string $key key
     *
     * @return string cookie valye
     */
    public static function read($key) {
        if (($value = self::verifyCookie($key)) !== false) {
            return $value;
        }

        return null;
    }

    /**
     * Verifies the cookie
     *
     * @param string $key key
     *
     * @return string cookie value if success
     */
    public static function verifyCookie($key) {
        if (empty($_COOKIE[$key])) {
            return false;
        }
        list($value, $expiration, $hmac) = explode('|', $_COOKIE[$key]);
        $expired = $expiration;

        if ($expired > 0 && $expired < time()) {
            return false;
        }

        $key = hash_hmac('md5', $value . $expiration, COOKIE_SECRET_KEY);
        $hash = hash_hmac('md5', $value . $expiration, $key);

        if ($hmac != $hash) {
            return false;
        }
        return $value;
    }

    /**
     * Writes the cookie
     *
     * @param string $key    key
     * @param string $value  value
     * @param string $expire expire
     * @throws \Exception
     * @return void
     */
    public static function write($key, $value = null, $expire = "") {
        if ($expire === '') {
            $expire = time() + COOKIE_EXPIRY_TIME;
        }
        $cookie = self::_generateCookie($value, $expire);
        if (!setcookie($key, $cookie, $expire, COOKIE_PATH, COOKIE_DOMAIN, false, false)) {
            throw new \Exception("Could not set cookie.");
        }
    }

    /**
     * Generates the cookie
     *
     * @param string $value      value
     * @param string $expiration expiration
     *
     * @return string cookie
     */
    private static function _generateCookie($value, $expiration) {
        $key = hash_hmac('md5', $value . $expiration, COOKIE_SECRET_KEY);
        $hash = hash_hmac('md5', $value . $expiration, $key);

        $cookie = $value . '|' . $expiration . '|' . $hash;

        return $cookie;
    }

    /**
     * Deletes the cookie
     *
     * @param string $key key
     *
     * @return void
     */
    public static function delete($key) {
        unset($_COOKIE[$key]);
        $expire = time() - 60 * 60 * 24 * 365 * 30;
        setcookie($key, "", $expire, COOKIE_PATH, COOKIE_DOMAIN, false, false);
    }

    /**
     * Return the cookie
     *
     * @param string $key key
     *
     * @return string cookie
     */
    public static function getCookie($key) {
        if (empty($_COOKIE[$key])) {
            return false;
        }
        return $_COOKIE[$key];
    }

}
