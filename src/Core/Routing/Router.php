<?php

namespace FlexPhp\Core\Routing;

use FlexPhp\Config\Routes\RouteConfig;
use FlexPhp\Controllers\BaseController;
use FlexPhp\Core\Http\Request;
use FlexPhp\Core\Http\Response;
use FlexPhp\Core\Contracts\Responsable;
use FlexPhp\Core\Routing\Attributes\Route as RouteAttribute;
use Stringable;

class Router
{
    protected array $routes = [];

    public function dispatch(Request $request) : Response
    {
        $response = null;
        foreach ($this->routes as $route) {
            if ($route->match($request->getUri(), $request->getMethod())) {
                $response = $route->run();
                break;
            }
        }
        return $this->toResponse($response);
    }

    public function toResponse(mixed $response) : Response
    {
        if (is_null($response)) {
            return new Response("Página no encontrada", 404, ['Content-Type' => 'text/html']); 
        }

        if ($response instanceof Responsable) {
            return $reponse->toResponse();
        }

        if ($response instanceof Stringable ||
            (is_object($response) && method_exists($response, '__toString'))
        ) {
            return new Response($response->__toString(), 200, ['Content-Type' => 'text/html']); 
        }

        if (is_array($response)) {
            return new Response(json_encode($response), 200, ['Content-Type' => 'text/html']); 
        }

        if (is_string($response) || is_array($response)) {
            return new Response($response, 200, ['Content-Type' => 'text/html']); 
        }

        return new Response("Error interno del servidor", 500, ['Content-Type' => 'text/html']); 
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