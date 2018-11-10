<?php
return [
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
    "headers.allow" => ["Content-Type", "Authorization", "If-Match", "If-Unmodified-Since"],
    "headers.expose" => ["Etag"],
    "credentials" => true,
    "cache" => 60,
    "logger" => $logger
];
