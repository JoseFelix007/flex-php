<?php

namespace FlexPhp\Core\Routing;

class Route
{
    protected array $methods;
    protected string $url;
    protected array $action;

    public function __construct($methods, $url, $action)
    {
        $this->methods = $methods;
        $this->url = $url;
        $this->action = $action;
    }

    public function match($url, $method)
    {
        return $this->matchUrl($url) && $this->matchMethod($method);
    }

    public function run()
    {
        call_user_func_array($this->action, []);
    }

    protected function matchUrl($url)
    {
        return $this->url === $url;
    }

    protected function matchMethod($method)
    {
        return in_array($method, $this->methods);
    }
}