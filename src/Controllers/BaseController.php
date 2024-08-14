<?php

namespace FlexPhp\Controllers;
use FlexPhp\Core\Routing\Attributes\Route;

class BaseController
{
    #[Route("/")]
    public function index()
    {
        echo "Hello From Controller";
    }
}