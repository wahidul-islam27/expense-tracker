<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ViewNotFoundException;

class View
{
    public function __construct(
        protected string $view,
        protected array $params = []
    ) {}

    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render(): string
    {

        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException("View not found: {$this->view}");
        }

        // Extract parameters to variables
        foreach ($this->params as $key => $value) {
            $$key = $value;
        }

        // Capture the view output
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // Capture the full layout output with $content available
        ob_start();
        include VIEW_PATH . '/layout.php';

        return (string) ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function __get(string $name)
    {
        return $this->params[$name] ?? null;
    }
}
