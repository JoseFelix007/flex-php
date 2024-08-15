<?php

namespace FlexPhp\Controllers;
use FlexPhp\Core\Routing\Attributes\Route;

class UserController extends BaseController
{
    #[Route("/")]
    public function index()
    {
        echo "Hello From Controller";
    }
}