<?php

declare(strict_types=1);

use Monolog\Logger;
use App\Preferences;
use Slim\Flash\Messages;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;



use Psr\Container\ContainerInterface;
use Monolog\Handler\RotatingFileHandler;

return [
    LoggerInterface::class => function (ContainerInterface $container): LoggerInterface {
        // Get the preferences from the container.
        $preferences = $container->get(Preferences::class);

        // Instantiate a new logger and push a handler into the logger.
        $logger = new Logger('converter');
        $logger->pushHandler(
            new RotatingFileHandler(
                $preferences->getRootPath() . '/logs/log.log'
            )
        );
        $logger->pushHandler(
            new StreamHandler('php://stdout', Logger::DEBUG)
        );

        return $logger;
    }
];
