<?php

namespace FlexPhp\Core\Routing;

use FlexPhp\Config\Routes\RouteConfig;
use FlexPhp\Core\Application;
use FlexPhp\Core\Routing\Router;

class RoutingService
{
    protected Application $app;
    protected Router $router;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $this->app['router'] = new Router();
        $this->loadRoutes();
    }

    public function boot()
    {
        //
    }

    protected function loadRoutes()
    {
        $router = $this->app['router'];
        $routeConfigFiles = glob(ROOT_PATH . '/src/Config/Routes/*RouteConfig.php');
        foreach ($routeConfigFiles as $routeConfigFile) {
            require_once $routeConfigFile; 

            $namespace = "FlexPhp\\Config\\Routes\\";
            $className = basename($routeConfigFile, '.php');
            $class = $namespace . $className;

            if (class_exists($class) && $className !== 'RouteConfig') {
                $routeConfig = new $class($router);

                if ($routeConfig instanceof RouteConfig) {
                    $router->loadRoutes($routeConfig);
                }
            }
        }
    }
}