<?php

namespace FlexPhp\Core\Routing\Attributes;

use Attribute;

#[Attribute]
class Route
{
    public string $url;
    public array $methods;

    public function __construct($url, $methods = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'])
    {
        $this->url = $url;
        $this->methods = $methods;
    }
}