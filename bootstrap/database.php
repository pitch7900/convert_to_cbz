<?php

$capsule = new \Illuminate\Database\Capsule\Manager;

$capsule->addConnection([
    'driver' => $_ENV['DB_DRIVER'],
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => $_ENV['DB_CHARSET'],
    'port' => $_ENV['DB_PORT'],
    'collation' => $_ENV['DB_COLLATION'],
    'prefix' => ''
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
