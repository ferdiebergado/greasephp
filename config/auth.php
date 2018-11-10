<?php
return [
    "path" => ["/auth", "/hello"],
    "secure" => true,
    "relaxed" => ["localhost:8787"],
    "ignore" => ["/auth/login"],
    'secret' => getenv('JWT_SECRET')
];
