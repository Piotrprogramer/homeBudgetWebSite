<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Sessions
 */
session_start();


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes  

$router->add('api/limit/{category:[a-zA-Z0-9żźćńółęąśŻŹĆĄŚĘŁÓŃ\s ]+}', ['controller' => 'Expense', 'action' => 'limit']);

//$router->add('api/spendMoney/{category}/{date}', ['controller' => 'Expense', 'action' => 'getSpendedMoney']);
$router->add('api/spendMoney/{category:[a-zA-Z0-9żźćńółęąśŻŹĆĄŚĘŁÓŃ\s ]+}/{date:[A-Za-z0-9\- ]+}', ['controller' => 'Expense', 'action' => 'getSpendedMoney']);

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);
$router->add('{controller}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
