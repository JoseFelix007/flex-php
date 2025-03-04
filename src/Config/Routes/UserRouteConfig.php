<?php

namespace FlexPhp\Config\Routes;

use FlexPhp\Controllers\UserController;

class UserRouteConfig extends RouteConfig
{
    public function registerRoutes()
    {
        $this->router->addController(new UserController());
    }
}
