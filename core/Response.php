<?php

/**
 *
 * Response class models the content of the HTTP response
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

/**
 * Request class
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */
class Response {

    /**
     * Body content of incoming request
     * @var str
     */
    private $_content;

    /**
     * Http status code
     * @var str
     */
    private $_status;

    /**
     * TTL value of the response
     */
    private $_ttl = 0;

    /**
     * Http status code array
     * @var str
     */
    private $_statusCodeArr;

    /**
     * content type to be sent
     * @var str
     */
    private $_contentType = 'application/json';

    /**
     * Holds the response headers
     * @var array
     */
    private $_headers = array();

    /**
     * Create a response object and sends the HTTP response
     */
    function __construct() {
        
    }

    /**
     * Fetches the content
     *
     * @return void
     */
    public function getContent() {
        return $this->_content;
    }

    /**
     * Sets the content
     *
     * @param str $content content
     *
     * @return void
     */
    public function setContent($content) {
        $this->_content = $content;
    }

    /**
     * Fetches the status
     *
     * @return void
     */
    public function getStatus() {
        return $this->_status;
    }

    /**
     * Sets the request status
     *
     * @param str $status request status 
     *
     * @return void
     */
    public function setStatus($status) {
        $this->_status = $status;
    }

    /**
     * Fetches the status
     *
     * @return void
     */
    public function getTTL() {
        return $this->_ttl;
    }

    /**
     * Sets the request status
     *
     * @param int $ttl time to live in seconds
     *
     * @return void
     */
    public function setTTL($ttl) {
        $this->_ttl = $ttl;
    }

    /**
     * Returns the content type
     *
     * @return str content type
     */
    public function getContentType() {
        return $this->_contentType;
    }

    /**
     * Sets the content type
     *
     * @param str $contentType content type
     *
     * @return void
     */
    public function setContentType($contentType) {
        $this->_contentType = $contentType;
    }

    /**
     * Returns the status code 
     * 
     * @return status code
     */
    public function getStatusCode() {
        $status = $this->status . '_CODE';
        if (isset($this->_statusCodeArr) && isset($this->_statusCodeArr[$status])) {
            return $this->_statusCodeArr[$status];
        }
        return false;
    }

    /**
     * Reruns the status code message
     *
     * @return status code message
     */
    public function getStatusCodeMsg() {
        $status = $this->status . '_MSG';
        if (isset($this->_statusCodeArr) && isset($this->_statusCodeArr[$status])) {
            return $this->_statusCodeArr[$status];
        }
        return false;
    }

    /**
     * sets the header
     *
     * @param str $header header
     *
     * @return void
     */
    public function setHeader($header) {
        $this->_headers[] = $header;
    }

    /**
     * Sends the response header
     *
     * @return void
     */
    public function sendResponseHeader() {
        $statusHeader = 'HTTP/1.1 ' . $this->getStatus() . ' ' . $this->getStatusCode();

        // set the status
        header($statusHeader);
        // set the content type
        header('Content-type: ' . $this->getContentType());
        $http_origin = $_SERVER['HTTP_ORIGIN'];
        $nakAllowedUrls = explode(",", UP_FE_URL);
        if (UP_CROSS_PROTOCOL == 'both') {
            foreach ($nakAllowedUrls as $nakAllowedUrl) {
                $httpOrigin[] = "https://" . $nakAllowedUrl;
                $httpOrigin[] = "http://" . $nakAllowedUrl;
            }
        } else if (UP_CROSS_PROTOCOL == 'http') {
            foreach ($nakAllowedUrls as $nakAllowedUrl) {
                $httpOrigin[] = "http://" . $nakAllowedUrl;
            }
        } else {
            foreach ($nakAllowedUrls as $nakAllowedUrl) {
                $httpOrigin[] = "https://" . $nakAllowedUrl;
            }
        }
        if (in_array($http_origin, $httpOrigin)) {
            header("Access-Control-Allow-Origin: $http_origin");
        }
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: content-type");
        header("Access-Control-Allow-Methods: GET, POST,OPTIONS");
    }

    /**
     * Sends the Cache header along with the response
     * 
     * @return void
     */
    public function setCacheHeaders() {
        $ttl = $this->getTTL();
        if ($ttl > 0) {
            $expire = "Expires: " . gmdate("D, d M Y H:i:s", time() + $ttl) . " GMT";
            $cacheControl = "Cache-Control: public, max-age=$ttl";
        } else {
            $expire = "Expires: " . gmdate("D, d M Y H:i:s", time()) . " GMT";
            $cacheControl = "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0";
        }
        $this->setHeader($expire);
        $this->setHeader($cacheControl);
    }

    /**
     * Sends the headers along with the response
     *
     * @return void
     */
    public function sendHeaders() {
        foreach ($this->_headers as $header) {
            header($header);
        }
    }

    /**
     * Redirect to the error page
     *
     * @return void
     */
    public function sendError() {
        //
    }

    /**
     * Sends the response
     *
     * @return void
     */
    public function sendResponse() {
        $this->sendResponseHeader();
        $this->setCacheHeaders();
        $this->sendHeaders();

        $content = $this->getContent();

        //TODO: Redirection

        echo $content;
        exit;
    }

}
