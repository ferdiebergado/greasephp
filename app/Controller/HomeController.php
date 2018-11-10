<?php declare (strict_types = 1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Controller\Controller;
use App\Model\User;

class HomeController extends Controller
{
    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        $message = "Hello from ferdie";
        $session = $request->getAttribute('session');
        $segment = $session->getSegment(get_class($this));
        $segment->setFlashNow('status', 'Welcome');
        return view("home.twig", compact('message', 'segment'), $this->twig, $this->response);
    }
}
