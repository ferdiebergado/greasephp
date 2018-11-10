<?php
return [
    ['GET', '/', ['HomeController', 'index']],
    ['GET', '/user/{user}', ['UserController', 'show']],
    ['GET', '/login', ['LoginController', 'show']],
    ['POST', '/login', ['LoginController', 'login']],
    // ['POST', '/auth/login', ['AuthController', 'login']]
];
