<?php


use Slim\App;

use App\Controllers\HomeController;


return function (App $app) {
        /**
         * Menu's calls
         */
        $app->get('/', HomeController::class . ':home')
                ->setName('home');       
};
