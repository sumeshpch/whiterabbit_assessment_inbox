<?php
/**
 * Controller base class. All controller classes would extend this.
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

/**
 * Parent Controller class. Inherited by all controllers
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */
class Controller
{
    /**
    * The request object
    * @var object
    */
    private $_request;

    /**
    * The reponse object
    * @var object
    */
    private $_response;

    /**
    * view template
    */
    protected $view;

    /*
    *  array to store variables to be used in view
    */
    protected $data = array();

    /**
     * Class constructor. Initializes class and
     * assigns the request and response object
     *
     * @param array $config config array
     *
     * @return void
     */
    function __construct(array $config=array())
    {
        if (!isset($config['request']) || !isset($config['response'])) {
            throw new \InvalidArgumentException("Request or Reponse object not set");
        }

        $this->setRequest($config['request']);
        $this->setResponse($config['response']);

    }

          /**
     * Sets the class name methode name of the requested URI
     *
     * @param String $class The request class and $method method we need to call
     *
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->_request = $request;
    }

    /**
     * Returns the request object
     *
     * @return request object
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Sets the response object
     *
     * @param object $response The response object
     *
     * @return null
     */
    public function setResponse(Response $response)
    {
        $this->_response = $response;
    }

    /**
     * Gets the response object
     * 
     * @return object response object
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Sets the View to be rendered
     *
     * @param str $folder folder containing the view
     * @param str $view   filename of the view
     *
     * @return void
     */
    public function setDefaultView($folder, $view)
    {
        $this->view = APP_PATH.'/views/'.strtolower($folder).'/'.$view.'.tpl.php';
    }

    /**
     * Sets the value for the key
     *
     * @param str $key   key
     * @param str $value value
     *
     * @return void
     */
    protected function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Sets the template
     *
     * @param str $view value
     *
     * @return void
     */
    protected function setTemplate($view)
    {
        $this->view = APP_PATH.$view;
    }

    /**
     * Sets variables
     *
     * @param str $vars variables
     *
     * @return void
     */
    protected function setVars($vars)
    {
        if (!is_array($vars)) {
            return false;
        }
        $this->data = array_merge($this->data, $vars);
    }

    /**
     * Renders the view
     *
     * @return void
     */
    protected function render()
    {
        try{
            extract($this->data);
            include $this->view;
        }
        catch(Exception $e){

            throw new \Exception('View, '.$this->view.' not found.');
        }
    }

    /**
     * Redirects
     *
     * @param str $url url
     *
     * @return void
     */
    public static function redirect($url)
    {
        //bug, Header may not contain more than a single header, new line detected, below line may solve it
        // $url = ereg_replace( ' +', '%20', $url);
        header("Location: $url");
        exit;
    }

            /**
     * sendResponse
     *
     * @param str $url url
     *
     * @return void
     */
    public function response($msg,$code=200,$type="html")
    {       
          $res =  $this->getResponse();
          $res->setStatus($code);
          if($type == "html"){
            $ctype = "text/html";
          }else if($type == "text"){
            $ctype = "text/plain";
          } else if($type == "json"){
            $msg =json_encode($msg);
            $ctype = "application/json";
          }
          $res->setContentType($ctype);
          $res->setContent($msg);

          $res->sendResponse();
    }


}
