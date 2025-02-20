<?php

require __DIR__ . '/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\ORMSetup;

$config = new PhpFile('config/migrations.php');

$paths = [__DIR__ . '/src/Entity'];
$isDevMode = true;

$ormConfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$dbParams = [
    'driver' => 'pdo_pgsql',
    'host' => 'database',
    'dbname' => 'app_db',
    'user' => 'app_user',
    'password' => 'app_pass',
];

$entityManager = EntityManager::create($dbParams, $ormConfig);

return DependencyFactory::fromEntityManager(
    $config,
    new ExistingEntityManager($entityManager)
);