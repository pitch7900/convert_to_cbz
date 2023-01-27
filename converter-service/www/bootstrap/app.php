<?php


use Dotenv\Dotenv;
use App\ContainerFactory;
use Slim\Factory\AppFactory;
use Dotenv\Exception\InvalidPathException;

// Set the absolute path to the root directory.
$rootPath = realpath(__DIR__ . '/..');
// Set the default timezone.
date_default_timezone_set('Europe/Zurich');

require __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv::createMutable($rootPath . '/config/');
    $dotenv->load();
    //Minimal values to run this website
} catch (InvalidPathException $e) {
    die("Unable to load configuration file");
}

//Load DB configuration
require_once __DIR__ . '/database.php';


// Create the container for dependency injection.
try {
    $container = ContainerFactory::create($rootPath);
} catch (Exception $e) {
    die($e->getMessage());
}


// Set the container to create the App with AppFactory.
AppFactory::setContainer($container);
$app = AppFactory::create();

// Set the cache file for the routes. Note that you have to delete this file
// whenever you change the routes.
// $app->getRouteCollector()->setCacheFile(
//     $rootPath . '/cache/routes.cache'
// );

//Call middleware functions
$_SERVER['app'] = &$app;

if (!function_exists('app')) {
    function app()
    {
        return $_SERVER['app'];
    }
}

//Add Middlewares
(require __DIR__ . '/middleware.php')($app);

// Register routes
(require __DIR__ . '/routes.php')($app);



// Add error handling middleware.
$displayErrorDetails = true;
$logErrors = true;
$logErrorDetails = false;
$app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);
