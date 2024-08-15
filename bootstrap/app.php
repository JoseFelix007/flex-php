<?php

use FlexPhp\Controllers\UserController;
use FlexPhp\Core\Routing\Router;

$router = new Router();
// $router->addRoute(["GET"], "/", [new BaseController(), "index"]);
$router->registerController(new UserController());

$router->dispatch();
