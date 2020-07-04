<?php

/**
 * This  file contains all routes in the application
 *
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */
use app\core\Router;

Router::add('/', ['GET' => ['class' => 'PublicManagement', 'action' => 'listEmails']]);
Router::add('/inbox', ['GET' => ['class' => 'PublicManagement', 'action' => 'listEmails']]);
Router::add('/email/([0-9]+)*', ['GET' => ['class' => 'PublicManagement', 'action' => 'getEmail']]);
Router::add('/email/delete', ['POST' => ['class' => 'PublicManagement', 'action' => 'deleteEmail']]);
