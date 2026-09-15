<?php
declare(strict_types=1);

/*
 * Bio Links · Desenvolvido por Alequizao <alequizao.dev@gmail.com>
 * https://github.com/alequizao · © 2026 Alequizao. Todos os direitos reservados.
 */
require_once __DIR__ . '/config.php';

function auth_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_name('biosess');
        session_start();
    }
}

function auth_check(): bool
{
    auth_start();
    return !empty($_SESSION['admin']);
}

function auth_login(string $user, string $pass): bool
{
    auth_start();
    if (hash_equals(ADMIN_USER, $user) && hash_equals(ADMIN_PASS, $pass)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        return true;
    }
    return false;
}

function auth_logout(): void
{
    auth_start();
    $_SESSION = [];
    session_destroy();
}

function auth_require(): void
{
    if (!auth_check()) {
        redirect('/admin');
    }
}

/** CSRF */
function csrf_token(): string
{
    auth_start();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_check(?string $token): bool
{
    auth_start();
    return !empty($_SESSION['csrf']) && is_string($token) && hash_equals($_SESSION['csrf'], $token);
}
