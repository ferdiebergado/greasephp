<?php

use DI\ContainerBuilder;
use function DI \{
    create, autowire, get, env
};
use Nyholm\Psr7\Factory\Psr17Factory;
use App\Controller\Controller;

// use Core\Middleware\BaseMiddleware;

$builder = new ContainerBuilder();
$builder->enableCompilation(TMP_PATH . 'container');
// $builder->useAutowiring(false);
$builder->useAnnotations(false);

$view = require(CONFIG_PATH . 'view.php');

$builder->addDefinitions([
    Psr17Factory::class => create(),
    'psr17factory' => get(Psr17Factory::class),
    Twig_Loader_Filesystem::class => create()->constructor(VIEW_PATH),
    'loader' => get(Twig_Loader_Filesystem::class),
    Twig_Environment::class => create()->constructor(get('loader'), $view),
    'twig' => get(Twig_Environment::class),
    // BaseMiddleware::class => create()->constructor(get('psr17factory')),
    Controller::class => create()->constructor(get('psr17factory'), get('twig')),
]);

$container = $builder->build();

return $container;
