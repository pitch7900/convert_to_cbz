<?php

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

// Set the absolute path to the root directory.
$rootPath = realpath(__DIR__ . '/..');
// Set the default timezone.
date_default_timezone_set('Europe/Zurich');

require __DIR__ . '/../vendor/autoload.php';



require_once $rootPath . '/app/Helpers/helpers.php';

var_dump(getenv());