<?php

/*** MIDDLEWARE STACK ***/

/* Middleware Dispatcher */
$broker = new Northwoods\Broker\Broker();

/* Session Manager */
$session = require_once(CONFIG_PATH . 'session.php');
// $broker->append(new Core\Middleware\SessionMiddleware($session['name'], $session['cookie_lifetime'], '/', null, null, $session['save_path']));

$broker->append((new Middlewares\AuraSession())->name($session['name']));

/* Router */
$router = require(CORE_PATH . 'router.php');
$broker->append(new Middlewares\FastRoute($router, new Nyholm\Psr7\Factory\Psr17Factory));

/* Request Handler */
$container = require(CORE_PATH . 'Container' . DS . 'container.php');
$broker->append(new Middlewares\RequestHandler($container));

// $broker->append(new Core\Middleware\HeadersMiddleware());

return $broker;
