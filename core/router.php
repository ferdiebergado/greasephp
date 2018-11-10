<?php

use function Fastroute\simpleDispatcher;

//Create the router dispatcher
$router = simpleDispatcher(function (\FastRoute\RouteCollector $r) {
    $namespace = "App\\Controller\\";
    $routes = include_once(CONFIG_PATH . 'routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], [$namespace . $route[2][0], $route[2][1]]);
    }
});

return $router;
