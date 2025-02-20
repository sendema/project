<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../src/Entity'],
    isDevMode: true,
);

$connection = DriverManager::getConnection([
    'driver' => 'pdo_pgsql',
    'host' => 'database',
    'dbname' => 'app_db',
    'user' => 'app_user',
    'password' => 'app_pass',
], $config);

return EntityManager::create($connection, $config);
