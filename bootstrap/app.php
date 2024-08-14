<?php

use FlexPhp\Controllers\BaseController;
use FlexPhp\Core\Routing\Router;

$router = new Router();
// $router->addRoute(["GET"], "/", [new BaseController(), "index"]);
$router->registerController(new BaseController());

$router->dispatch();
