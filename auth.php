<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['usuario_id']);
}

function isAdmin(): bool {
    return isLoggedIn() && ($_SESSION['usuario_tipo'] ?? '') === 'admin';
}

function isCliente(): bool {
    return isLoggedIn() && ($_SESSION['usuario_tipo'] ?? '') === 'cliente';
}

function requireLogin(string $redirect = '../login.php'): void {
    if (!isLoggedIn()) {
        header('Location: ' . $redirect);
        exit;
    }
}

function requireAdmin(): void {
    if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
    if (!isAdmin())    { header('Location: ../index.php'); exit; }
}

function requireCliente(): void {
    if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
    if (!isCliente())  { header('Location: ../index.php'); exit; }
}

function usuarioLogado(): ?array {
    if (!isLoggedIn()) return null;
    return [
        'id'    => $_SESSION['usuario_id'],
        'nome'  => $_SESSION['usuario_nome'],
        'email' => $_SESSION['usuario_email'],
        'tipo'  => $_SESSION['usuario_tipo'],
    ];
}

function iniciarSessao(array $usuario): void {
    $_SESSION['usuario_id']    = $usuario['id'];
    $_SESSION['usuario_nome']  = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['usuario_tipo']  = $usuario['tipo'];
}

function encerrarSessao(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
