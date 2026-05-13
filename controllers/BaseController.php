<?php

class BaseController
{
    protected function view(string $view, array $data = []): void
    {
        // Configuración global (nombre del negocio, moneda, ITBIS, etc.)
        $appSettings = [];
        try {
            if (class_exists('Setting')) {
                $appSettings = (new Setting())->getAll();
            }
        } catch (Throwable $e) {
            // Si la BD no está lista aún, no bloquear la carga de la UI.
            $appSettings = [];
        }

        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            echo "Vista no encontrada: " . htmlspecialchars($view);
            exit;
        }

        require __DIR__ . '/../views/layout.php';
    }

    protected function redirect(string $route, array $params = []): void
    {
        $url = '/?r=' . urlencode($route);
        foreach ($params as $k => $v) {
            $url .= '&' . urlencode((string)$k) . '=' . urlencode((string)$v);
        }
        header('Location: ' . $url);
        exit;
    }

    protected function requestMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    protected function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    protected function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
