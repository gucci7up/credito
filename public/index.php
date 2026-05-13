<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/env.php';
env_load(__DIR__ . '/../.env');

spl_autoload_register(function ($class) {
    $base = __DIR__ . '/..';
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $paths = [
        $base . '/controllers/' . $class . '.php',
        $base . '/models/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (is_file($p)) {
            require_once $p;
            return;
        }
    }
});

// Router simple: /?r=controller/action
$route = $_GET['r'] ?? 'dashboard/index';
$route = trim((string)$route, '/');
[$controller, $action] = array_pad(explode('/', $route, 2), 2, 'index');

$controllerClass = ucfirst(strtolower($controller)) . 'Controller';
$actionMethod = strtolower($action) . 'Action';

if (!class_exists($controllerClass)) {
    http_response_code(404);
    echo "Controlador no encontrado.";
    exit;
}

$c = new $controllerClass();
if (!method_exists($c, $actionMethod)) {
    http_response_code(404);
    echo "Acción no encontrada.";
    exit;
}

$c->$actionMethod();

