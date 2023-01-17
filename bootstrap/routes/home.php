<?php


use Slim\App;

use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;

return function (App $app) {
   
        $app->get('/status.json', HomeController::class . ':status')
                ->add(new AuthMiddleware($app))
                ->setName('status.json');  
                
        $app->get('/', HomeController::class . ':convert')
                ->add(new AuthMiddleware($app))
                ->setName('convert');
};
