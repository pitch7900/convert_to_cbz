<?php

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

// Set the absolute path to the root directory.
$rootPath = realpath(__DIR__ . '/..');
// Set the default timezone.
date_default_timezone_set('Europe/Zurich');

require __DIR__ . '/../vendor/autoload.php';

try {
    // $dotenv = Dotenv::createImmutable($rootPath .'/config/');
    $dotenv = Dotenv::createMutable($rootPath . '/config/');
    $dotenv->load();
} catch (InvalidPathException $e) {
    die("Unable to load configuration file");
}

require_once $rootPath . '/app/Helpers/helpers.php';

//Load DB configuration
require_once $rootPath . '/bootstrap/database.php';