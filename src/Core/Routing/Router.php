<?php

namespace FlexPhp\Core\Routing;

use FlexPhp\Config\Routes\RouteConfig;
use FlexPhp\Controllers\BaseController;
use FlexPhp\Core\Routing\Attributes\Route as RouteAttribute;
use Stringable;

class Router
{
    protected array $routes = [];

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $response = null;

        foreach ($this->routes as $route) {
            if ($route->match($requestUri, $requestMethod)) {
                $response = $route->run();
                break;
            }
        }

        if (is_null($response)) {
            http_response_code(404);
            return "Página no encontrada";
        }
        
        if ($response instanceof Stringable) {
            return $response; 
        } elseif (is_string($response) || is_array($response)) {
            return $response; 
        } elseif (is_object($response) && method_exists($response, '__toString')) {
            return $response; 
        } else {
            http_response_code(500);
            return "Error interno del servidor";
        }
    }

    public function loadRoutes(RouteConfig $routeConfig)
    {
        $routeConfig->registerRoutes();
    }

    public function addRoute($methods, $url, $action) {
        $this->routes[] = $this->newRoute($methods, $url, $action);
    }

    public function addController(BaseController $controller)
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