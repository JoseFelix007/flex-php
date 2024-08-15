<?php

namespace FlexPhp\Core\View;

use InvalidArgumentException;
use Stringable;

class View implements Stringable
{
    protected string $view;
    protected string $path;
    protected array $data;

    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
        $this->path = "";
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function render(): string
    {
        if (empty($this->path)) {
            return "";
        }

        extract($this->data);
        ob_start();
        require $this->path;
        $content = ob_get_clean();
        return $content;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    protected function normalize(string $name) : string
    {
        return str_replace('/', '.', strtolower($name));
    }

    protected function find(string $view) : string
    {
        $viewPath = ROOT_PATH . '/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new InvalidArgumentException("View [{$view}] not found.");
        }
        return $viewPath;
    }

    public static function load(string $view, array $data = []) : View
    {
        $instance = new View($view, $data);
        $name = $instance->normalize($view);
        $path = $instance->find($name);
        $instance->setPath($path);
        return $instance;
    }
}