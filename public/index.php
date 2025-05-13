<?php

use App\Config;
use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;
use App\App;
use App\Container;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('VIEW_PATH', dirname(__DIR__) . '/views');

$baseUrl = '/expense-tracker/public';
$container = new Container();
$router = new Router($container);

// $requestUri = str_replace('/expense-tracker/src', '', $_SERVER['REQUEST_URI'],);

$router
    ->get($baseUrl . '/', [HomeController::class, 'index'])
    ->get($baseUrl . '/add-expense', [HomeController::class, 'addExpense'])
    ->post($baseUrl . '/add-expense', [HomeController::class, 'addExpense'])
    ->get($baseUrl . '/edit-expense', [HomeController::class, 'editExpense'])
    ->post($baseUrl . '/edit-expense', [HomeController::class, 'editExpense'])
    ->post($baseUrl . '/delete', [HomeController::class, 'delete'])
    ->get($baseUrl . '/report', [HomeController::class, 'report'])
    ->get($baseUrl . '/download', [HomeController::class, 'download'])

    ->get($baseUrl . '/register', [UserController::class, 'createUser'])
    ->post($baseUrl . '/register', [UserController::class, 'createUser'])
    ->get($baseUrl . '/login', [UserController::class, 'login'])
    ->post($baseUrl . '/login', [UserController::class, 'login'])
    ->get($baseUrl . '/logout', [UserController::class, 'logout'])
    ->get($baseUrl . '/profile', [UserController::class, 'profile'])
    ->post($baseUrl . '/profile', [UserController::class, 'profile'])
    ->get($baseUrl . '/forget', [UserController::class, 'forget'])
    ->post($baseUrl . '/forget', [UserController::class, 'forget'])
    ->get($baseUrl . '/confirm', [UserController::class, 'confirm'])
    ->post($baseUrl . '/confirm', [UserController::class, 'confirm'])



    ->get($baseUrl . '/category', [CategoryController::class, 'createCategory'])
    ->post($baseUrl . '/category', [CategoryController::class, 'createCategory'])
    ->post($baseUrl . '/category/delete', [CategoryController::class, 'delete'])
    ->get($baseUrl . '/category/update', [CategoryController::class, 'update'])
    ->post($baseUrl . 'category/update', [CategoryController::class, 'update']);


(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    new Config($_ENV)
))->run();

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
