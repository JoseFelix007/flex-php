<?php

namespace FlexPhp\Core;

use FlexPhp\Core\Container\Container;
use FlexPhp\Core\Routing\RoutingService;

class Application extends Container
{
    public function __construct() {
        $this->registerService(new RoutingService($this));
    }

    public function registerService($service)
    {
        $service->register();
        $service->boot();
    }

    public function getRequest()
    {
        return '';
    }

    public function sendRequestToRouter($request)
    {
        $router = $this->get('router');
        return $router->dispatch();
    }

    public function run()
    {
        $request = $this->getRequest();
        $response = $this->sendRequestToRouter($request);

        echo $response;
        return $response;
    }
}