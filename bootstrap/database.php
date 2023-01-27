<?php

use App\Database\CreateLogsDBTable;

$capsule = new Illuminate\Database\Capsule\Manager;

$capsule->addConnection([
    'driver' => $_ENV['DB_DRIVER'],
    'host' => $_ENV['DB_HOST'],
    'database' => __DIR__."/../".$_ENV['DB_DATABASE'],
    // 'username' => $_ENV['DB_USERNAME'],
    // 'password' => $_ENV['DB_PASSWORD'],
    'charset' =>  $_ENV['DB_CHARSET'],
    'port' => $_ENV['DB_PORT'],
    'collation' => $_ENV['DB_COLLATION'],
    'prefix' => '',
    'schema' => $_ENV['DB_SCHEMA']
]);
$capsule->setAsGlobal();


$capsule->setAsGlobal();
$capsule->bootEloquent();



