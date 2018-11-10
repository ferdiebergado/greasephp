<?php

namespace Core\Middleware;

use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Harmony\Exception\RouteNotFound;
use WoohooLabs\Harmony\Exception\MethodNotAllowed;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use FastRoute\Dispatcher;

class RouteRequestMiddleware extends FastRouteMiddleware
{
    /**
     * @var ResponseInterface
     */
    protected $errorResponsePrototype;

    public function __construct(Dispatcher $fastRoute = null, string $actionAttributeName = "__action", ResponseInterface $errorResponsePrototype)
    {
        parent::__construct($fastRoute, $actionAttributeName);
        $this->errorResponsePrototype = $errorResponsePrototype;
    }

    /**
     * @throws MethodNotAllowed
     * @throws RouteNotFound
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            $request = $this->routeRequest($request);
        } catch (RouteNotFound $e) {
            return $this->errorResponsePrototype->withStatusCode(404);
        } catch (MethodNotAllowed $e) {
            return $this->errorResponsePrototype->withStatusCode(405);
        }
        return $handler->handle($request);
    }

}
