<?php

namespace FlexPhp\Core\Contracts;

use FlexPhp\Core\Http\Request;
use FlexPhp\Core\Http\Response;

interface Responsable
{
    public function toResponse(Request $request) : Response;
}
