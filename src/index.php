<?php

use App\Config;
use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;
use App\App;

require_once __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


(new App(new Config($_ENV)))->run();

// $params  = [
//     'host'      => $_ENV['DB_HOST'],
//     'user'      => $_ENV['DB_USER'],
//     'password'  => $_ENV['DB_PASS'],
//     'dbname'    => $_ENV['DB_DATABASE'],
//     'driver'    => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
// ];

// $entityManager = EntityManager::create($params, Setup::createAttributeMetadataConfiguration([__DIR__ . '/Entities']));

// $user = (new User());

// $user->setUsername("test-user");
// $user->setMonthlyIncome("10000");
// $user->setIncomeAddDate(new DateTime());



// $entityManager->persist($user);
// $entityManager->flush();
