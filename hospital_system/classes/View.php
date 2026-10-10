<?php
/**
 * Renders frontend templates from assets/frontend/templates.
 * PHP pages only prepare data and call View::render(); they contain no HTML.
 */
class View
{
    // Render a page template inside a layout (default layout: layouts/main.php)
    public static function render(string $template, array $data = [], string $layout = 'main'): void
    {
        require_once FRONTEND_PATH . '/helpers.php';

        $data['flash'] = $data['flash'] ?? getFlash();
        $viewFile = self::path($template);

        extract($data, EXTR_SKIP);
        require self::path('layouts/' . $layout);
    }

    // Include a reusable piece (nav, flash message, ...)
    public static function partial(string $name, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require self::path('partials/' . $name);
    }

    private static function path(string $name): string
    {
        $file = FRONTEND_PATH . '/templates/' . $name . '.php';
        if (!is_file($file)) {
            die('Template not found: ' . htmlspecialchars($name));
        }
        return $file;
    }
}
