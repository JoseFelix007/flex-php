<?php

namespace FlexPhp\Core\Container;

use ArrayAccess;

class Container implements ArrayAccess
{
    protected array $services;

    public function set($id, $concrete)
    {
        $this->services[$id] = $concrete;
    }

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new \Exception("Servicio '$id' no encontrado en el contenedor.");
        }
        return $this->resolve($id);
    }

    public function has($id) : bool
    {
        return isset($this->services[$id]);
    }

    public function resolve($id)
    {
        $concrete = $this->services[$id];
        if (is_callable($concrete)) {
            return $concrete($this);
        } elseif (is_string($concrete) && class_exists($concrete)) {
            $reflectionClass = new \ReflectionClass($concrete);
            $constructor = $reflectionClass->getConstructor();

            if ($constructor) {
                $dependencies = [];
                foreach ($constructor->getParameters() as $parameter) {
                    $dependencyType = $parameter->getType();
                    if ($dependencyType) {
                        $dependencies[] = $this->get($dependencyType->getName());
                    }
                }
                return $reflectionClass->newInstanceArgs($dependencies);
            } else {
                return new $concrete();
            }
        } else {
            return $concrete;
        }
    }

    public function offsetExists($key): bool
    {
        return $this->has($key);
    }

    public function offsetGet($key): mixed
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value): void
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key): void
    {
        unset($this->services[$key]);
    }
}
