<?php

namespace FlexPhp\Controllers;
use FlexPhp\Core\Routing\Attributes\Route;
use FlexPhp\Core\View\View;

class UserController extends BaseController
{
    #[Route("/")]
    public function index()
    {
        return View::load("home", ["name" => "Peter"]);
    }
}
