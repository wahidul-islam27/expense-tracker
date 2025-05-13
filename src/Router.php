<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouterNotFoundException;
use APp\Container;

class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
        
    }

    public function register(string $requestMethod, string $route, callable|array $action): self
    {
        $this->routes[$requestMethod][$route] = $action;

        return $this;
    }

    public function get(string $route, callable|array $action): self
    {
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable|array $action): self
    {
        return $this->register('post', $route, $action);
    }

    public function put(string $route, callable|array $action): self
    {
        return $this->register('put', $route, $action);
    }

    public function delete(string $route, callable|array $action): self
    {
        return $this->register('delete', $route, $action);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function resolve(string $requestUri, string $requestMethod)
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        // echo '<pre>';
        // var_dump($this->routes);
        // echo '</pre>';

        if (! $action) {
            echo 'action not found';
            throw new RouterNotFoundException();
        }

        if (is_callable($action)) { // this function is check if the $action is callable?
            echo 'hit in the expcetion';
            return call_user_func($action); // it is used to call a callable function.
        }

        [$class, $method] = $action;

        if (class_exists($class)) {
            $class = $this->container->get($class);

            if (method_exists($class, $method)) {
                return call_user_func_array([$class, $method], []); // it will call the user function with array parameter.
            }
        }

        throw new RouterNotFoundException();
    }
}
