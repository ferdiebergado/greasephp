<?php
return [
    Controller::class => DI\create()->constructor(DI\create(Psr17Factory::class)),
    HomeController::class => DI\autowire(),
    AuthController::class => DI\autowire()->constructor(DI\get(DatabaseInterface::class)),
    DatabaseInterface::class => DI\create(EasydbDatabase::class)->constructor(DI\create(PDO::class)->constructor($dsn, DI\env('DB_USER'), DI\env('DB_PASS'), null))
];
