<?php

declare(strict_types=1);

namespace App;

use App\Config;
use App\DB;
use App\Repositories\ExpenseRepository;
use App\Services\ExpenseService;
use DateTime;
use App\Exceptions\RouterNotFoundException;
use App\Container;
use App\Repositories\UserRepository;
use App\Services\UserService;

class App
{
    private static DB $db;
    private static Container $container;

    public function __construct(protected Router $router, protected array $request, protected Config $config)
    {
        static::$db = new DB($config->db ?? []);
        static::$container = new Container();

        static::$container->set(UserService::class, function (Container $c) {
            return new UserService($c->get(UserRepository::class));
        });

        static::$container->set(UserRepository::class, fn() => new UserRepository());
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run()
    {
        try {
            echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
        } catch (RouterNotFoundException) {
            http_response_code(404);
        }
    }
}
