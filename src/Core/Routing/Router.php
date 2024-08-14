<?php

namespace FlexPhp\Core\Routing;

use FlexPhp\Controllers\BaseController;
use FlexPhp\Core\Routing\Attributes\Route as RouteAttribute;

class Router
{
    protected array $routes = [];

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route->match($requestUri, $requestMethod)) {
                $route->run();
                return;
            }
        }

        http_response_code(404);
        echo "Página no encontrada";
    }

    public function addRoute($methods, $url, $action) {
        $this->routes[] = $this->newRoute($methods, $url, $action);
    }

    public function registerController(BaseController $controller)
    {
        $reflectionClass = new \ReflectionClass($controller);

        foreach ($reflectionClass->getMethods() as $method) {
            $attributes = $method->getAttributes(RouteAttribute::class);
            foreach ($attributes as $attribute) {
                $route = $attribute->newInstance();
                $this->addRoute($route->methods, $route->url, [$controller, $method->getName()]); 
            }
        }
    }

    protected function newRoute($methods, $url, $action)
    {
        return (new Route($methods, $url, $action));
    }
}