<?php
namespace Core\Middleware\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Core\Database;
use Firebase\JWT\JWT;

class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseInterface
     */
    protected $errorResponse;

    public function __construct(DatabaseInterface $db, ResponseInterface $response)
    {
        $this->db = $db;
        $this->errorResponse = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // Return Error 401 "Unauthorized" if the provided API key doesn't match the needed one
        $token = str_ireplace('bearer ', '', $request->getHeader('Authorization'));
        $decoded = JWT::decode($token, getenv('JWT_SECRET'), array('HS256'));
        $user = $this->db->row("SELECT id FROM users WHERE apikey = ?", $decoded['sub']['apikey']);
        if (empty($user)) {
            $this->errorResponse->getBody()->write(jsonize($user));
            return $this->errorResponse->withStatus(401);
        }
        
        // Invoke the remaining middleware if authentication was successful
        return $handler->handle($request);
    }
}
