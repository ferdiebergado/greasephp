<?php
namespace Core\Middleware;

use Nyholm\Psr7\Factory\Psr17Factory;

class BaseMiddleware
{
    /**
     * @var Psr17Factory
     */
    protected $response;

    public function __construct(Psr17Factory $responseFactory)
    {
        $this->response = $responseFactory->createResponse();
    }
}
