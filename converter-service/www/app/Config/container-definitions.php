<?php

declare(strict_types=1);

use App\Preferences;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;



use Slim\Flash\Messages;

return [
    LoggerInterface::class => function (ContainerInterface $container): LoggerInterface {
        // Get the preferences from the container.
        $preferences = $container->get(Preferences::class);

        // Instantiate a new logger and push a handler into the logger.
        $logger = new Logger('Template');
        $logger->pushHandler(
            new RotatingFileHandler(
                $preferences->getRootPath() . '/logs/log.log'
            )
        );

        return $logger;
    }
];
