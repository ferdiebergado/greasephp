<?php declare (strict_types = 1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Controller\Controller;
use App\Model\User;

class UserController extends Controller
{
    public function show(ServerRequestInterface $request) : ResponseInterface
    {
        $user = User::find($request->getAttribute('user'));
        return view("home.twig", compact('user'), $this->twig, $this->response);
    }
}
