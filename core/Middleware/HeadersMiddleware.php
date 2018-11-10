<?php
namespace Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Core\Middleware\BaseMiddleware;

class HeadersMiddleware extends BaseMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $this->response = $handler->handle($request);
        $headers = require(CONFIG_PATH . 'headers.php');
        foreach ($headers as $key => $value) {
            $this->response->withHeader($key, $value);
        }

        return $this->response;
    }
}
