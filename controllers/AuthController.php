<?php

class AuthController extends BaseController
{
    private function expectedUser(): string
    {
        return (string)env('AUTH_USER', 'admin');
    }

    private function verifyPassword(string $password): bool
    {
        $hash = (string)env('AUTH_PASS_HASH', '');
        if ($hash !== '') {
            return password_verify($password, $hash);
        }

        $plain = (string)env('AUTH_PASS', 'admin123');
        return hash_equals($plain, $password);
    }

    private function ensureCsrf(): string
    {
        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
        }
        return (string)$_SESSION['csrf_token'];
    }

    public function loginAction(): void
    {
        if (!empty($_SESSION['auth_user'])) {
            $this->redirect('dashboard/index');
        }

        $next = (string)$this->get('next', '');
        $error = '';

        if ($this->requestMethod() === 'POST') {
            $token = (string)$this->post('csrf_token', '');
            if (!hash_equals((string)($_SESSION['csrf_token'] ?? ''), $token)) {
                $error = 'Sesión inválida. Intenta de nuevo.';
            } else {
                $user = trim((string)$this->post('user', ''));
                $pass = (string)$this->post('pass', '');
                if ($user === '' || $pass === '') {
                    $error = 'Completa usuario y contraseña.';
                } elseif (!hash_equals($this->expectedUser(), $user) || !$this->verifyPassword($pass)) {
                    $error = 'Credenciales incorrectas.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['auth_user'] = $user;

                    if ($next !== '' && str_starts_with($next, '/')) {
                        header('Location: ' . $next);
                        exit;
                    }
                    $this->redirect('dashboard/index');
                }
            }
        }

        $settings = [];
        try {
            $settings = (new Setting())->getAll();
        } catch (Throwable $e) {
            $settings = [];
        }

        $csrf = $this->ensureCsrf();
        $viewFile = __DIR__ . '/../views/auth/login.php';
        require $viewFile;
    }

    public function logoutAction(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        header('Location: /?r=auth/login');
        exit;
    }
}
