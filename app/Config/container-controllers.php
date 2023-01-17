<?php

declare(strict_types=1);


use Psr\Container\ContainerInterface;

use App\Controllers\HomeController;


return [
    HomeController::class => function (ContainerInterface $container): HomeController {
        return new HomeController($container);
    }
];
