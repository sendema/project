<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set(EntityManager::class, function() {
    return require __DIR__ . '/../config/doctrine.php';
});

$container->set(App\Controller\RegistrationController::class, function(Container $container) {
    return new App\Controller\RegistrationController(
        new App\Service\RegistrationService(
            $container->get(EntityManager::class)
        )
    );
});

AppFactory::setContainer($container);
$app = AppFactory::create();

$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

$app->run();