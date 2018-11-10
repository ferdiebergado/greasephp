<?php declare (strict_types = 1);

namespace App\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;

class Controller
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var Psr17Factory
     */
    protected $response;

    public function __construct(Psr17Factory $responseFactory, \Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->response = $responseFactory->createResponse();
    }
}
