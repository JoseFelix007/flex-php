<?php

namespace FlexPhp\Config\Routes;

use FlexPhp\Core\Routing\Router;

abstract class RouteConfig
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    abstract public function registerRoutes();
}
