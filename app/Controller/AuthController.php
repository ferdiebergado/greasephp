<?php declare (strict_types = 1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use DateTime;
use Firebase\JWT\JWT;
use Core\Database\DatabaseInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use App\Controller\Controller;

class AuthController extends Controller
{
    /**
     * @var DatabaseInterface
     */
    private $db;

    /**
     * Set database.
     * 
     * @param DatabaseInterface
     */
    public function __construct(DatabaseInterface $db, Psr17Factory $responseFactory)
    {
        parent::__construct($responseFactory);
        $this->db = $db;
    }

    public function login(ServerRequestInterface $request) : ResponseInterface
    {
        $statuscode = 401;
        $body = $request->getParsedBody();
        $user = $this->db->row("SELECT id, name, email, active, password FROM users WHERE email = ? AND active = true", $body['email']);

        if (isset($user)) {
            $hash = $user['password'];
            if (password_verify($body['password'], $hash)) {
                if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                    $newhash = password_hash($password, PASSWORD_DEFAULT);
                    $this->db->update('users', ['password' => $newhash], ['id' => $user['id']]);
                }
                $data = $this->issueToken($request->withAttribute('user', ['name' => $user['name'], 'email' => $user['email'], 'id' => $user['id']]));
                $this->db->update('users', [
                    'apikey' => $data['token'],
                    'last_login' => date(DATE_FORMAT_SHORT)
                ], ['id' => $user['id']]);
                // $authuser = $this->db->row("SELECT id, name, email, active, last_login FROM users WHERE id = ?", $user['id']);
                // cache_remember('user_' . $user['id'], 30, $authuser);
                $statuscode = 200;

            } else {
                $data = [
                    'error' => 'Invalid username or password.'
                ];
            }
        }

        $this->response
            ->getBody()
            ->write(jsonize($data));

        return $this->response->withStatus($statuscode);
    }

    protected function issueToken(ServerRequestInterface $request) : array
    {
        $now = new DateTime();
        $future = new DateTime("now +2 hours");
        $subject = $request->getAttribute('user');

        $jti = base64_encode(random_bytes(16));

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => $jti,
            "sub" => $subject,
            // "scope" => $scopes
        ];

        $secret = getenv("JWT_SECRET");
        $token = JWT::encode($payload, $secret, "HS256");

        $data["token"] = $token;
        $data["expires"] = $future->getTimeStamp();

        return $data;
    }
}
