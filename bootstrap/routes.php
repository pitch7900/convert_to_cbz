<?php

use Slim\App;

return function (App $app) {
        // Register routes
        $home = require __DIR__ . '/routes/home.php';
        $home($app);
};
