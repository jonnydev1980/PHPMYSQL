<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}
require_once __DIR__ . '/../config.php';

function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function is_logged_in(): bool {
    return !empty($_SESSION['id']);
}
function is_admin(): bool {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 'true';
}
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
function require_admin(): void {
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('Nuk ke leje per kete veprim.');
    }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!$sent || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('Seanca e formularit ka skaduar. Kthehu dhe provo perseri.');
    }
}
function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash']; unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($f['type']) . ' alert-dismissible fade show" role="alert">' . e($f['message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}
function page_start(string $title, string $active = ''): void {
    $name = $_SESSION['username'] ?? 'Vizitor';
    $admin = is_admin();
    echo '<!doctype html><html lang="sq"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content="Movies Management System"><title>' . e($title) . ' | CineManager</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/style.css" rel="stylesheet"></head><body>';
    echo '<nav class="navbar navbar-expand-lg navbar-dark topbar"><div class="container-fluid px-4"><a class="navbar-brand fw-bold" href="home.php"><span class="brand-mark">C</span> CineManager</a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="mainNav"><div class="navbar-nav me-auto">';
    $links = ['home.php'=>'Filmat', 'bookings.php'=>'Rezervimet'];
    if ($admin) { $links['dashboard.php']='Dashboard'; $links['list_movies.php']='Menaxho filmat'; }
    foreach ($links as $url=>$label) echo '<a class="nav-link ' . ($active === $url ? 'active' : '') . '" href="' . $url . '">' . $label . '</a>';
    echo '</div><div class="d-flex align-items-center gap-3 text-white-50"><span class="small">' . e($name) . ($admin ? ' · Admin' : ' · Klient') . '</span><a class="btn btn-sm btn-outline-light" href="logout.php">Dil nga llogaria</a></div></div></div></nav><main class="container-fluid page-wrap px-4 px-lg-5"><div class="page-heading"><div><div class="eyebrow">MOVIES MANAGEMENT SYSTEM</div><h1>' . e($title) . '</h1></div></div>';
    show_flash();
}
function page_end(): void {
    echo '</main><footer class="footer"><div class="container-fluid px-4 px-lg-5">CineManager <span>·</span> Movies Management System</div></footer><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>';
}
