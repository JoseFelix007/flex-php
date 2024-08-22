<?php

namespace FlexPhp\Core\Http;

use ArrayAccess;

class Request implements ArrayAccess
{
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

    protected array $query;
    protected array $request;
    protected array $cookies;
    protected array $files;
    protected array $server;
    protected ?string $content;

    protected string $uri;
    protected string $method;

    public function __construct()
    {
        $this->query = $_GET;
        $this->request = $_POST;
        $this->cookies = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->content = file_get_contents('php://input');

        $this->uri = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $this->server['REQUEST_METHOD'];
    }

    public function getUri(): string 
    {
        return $this->uri;
    }

    public function getMethod(): string 
    {
        return $this->method;
    }

    public function getHeader(string $name): ?string
    {
        $name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $this->server[$name] ?? null;
    }

    public function getIp(): ?string
    {
        return $this->server['REMOTE_ADDR'] ?? null;
    }

    public function getSegment(int $index): ?string
    {
        $segments = explode('/', trim($this->uri, '/'));
        return $segments[$index] ?? null;
    }

    public function getParam(string $name, $default = null)
    {
        return $this->query[$name] ?? $default;
    }

    public function getBodyAttribute(string $name, $default = null)
    {
        return $this->request[$name] ?? $default;
    }

    public function offsetExists($offset): bool
    {
        return in_array($offset, $this->getSource());
    }

    public function offsetGet($offset): mixed
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->getSource()[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->getSource()[$offset]);
    }

    public function __isset($key)
    {
        return ! is_null($this->__get($key));
    }

    public function __get($key)
    {
        return $this->getSource()[$key];
    }

    protected function getSource() : array
    {
        return in_array($this->getMethod(), ['GET', 'HEAD']) ? $this->query : $this->request;
    }
}