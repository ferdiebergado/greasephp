<?php

namespace App\Controller;

use App\Controller\Controller;
use Aura\Auth\Verifier\PasswordVerifier;
use Core\Session\AuraSession;
use Aura\Session\Session;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Session\Phpfunc;
use Aura\Session\SegmentFactory;
use Aura\Session\CsrfTokenFactory;
use Aura\Session\Randval;

class LoginController extends Controller
{
    public function show()
    {
        return view("sections/login.twig", array(), $this->twig, $this->response);
    }

    public function login(ServerRequestInterface $request) : ResponseInterface
    {
        $phpfunc = new Phpfunc;
        $custom_session = new AuraSession(new Session(
            new SegmentFactory,
            new CsrfTokenFactory(new Randval($phpfunc)),
            $phpfunc
        ));
        $auth_factory = new \Aura\Auth\AuthFactory($_COOKIE, $custom_session);
        $auth = $auth_factory->newInstance();
        $dsn = getenv('DB_DRIVER') . ":host=" . getenv('DB_HOST') . ";port=" . getenv('DB_PORT') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
        $pdo = new \PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
        $hash = new PasswordVerifier(PASSWORD_BCRYPT);
        $cols = array(
            'email', // "AS username" is added by the adapter
            'password', // "AS password" is added by the adapter
            'last_login',
        );
        $from = 'users';
        $where = 'active = 1';
        $pdo_adapter = $auth_factory->newPdoAdapter($pdo, $hash, $cols, $from, $where);
        $login_service = $auth_factory->newLoginService($pdo_adapter);
        $creds = $request->getParsedBody();
        try {
            $login_service->login($auth, array(
                'username' => $creds['email'],
                'password' => $creds['password']
            ));
        } catch (\Aura\Auth\Exception\UsernameNotFound $e) {

            $msg = "The username you entered was not found.";

        } catch (\Aura\Auth\Exception\MultipleMatches $e) {

            $msg = "There is more than one account with that username.";

        } catch (\Aura\Auth\Exception\PasswordIncorrect $e) {

            $msg = "The password you entered was incorrect.";

        } catch (InvalidLoginException $e) {

            $msg = "Invalid login details. Please try again.";

        }

        $session = $request->getAttribute('session');
        $segment = $session->getSegment(get_class($this));

        if ($auth->isValid()) {
            $segment->setFlashNow('status', 'Login successful!');
            return view("home.twig", compact('segment'), $this->twig, $this->response);
        }

        $segment->setFlashNow('error', $msg);
        return view("home.twig", compact('segment'), $this->twig, $this->response);
    }
}
