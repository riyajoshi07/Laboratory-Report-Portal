<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config.php';

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function logged_in(): bool {
    return !empty($_SESSION['user']);
}

function user(): array {
    return $_SESSION['user'] ?? [];
}

function require_login(): void {
    if (!logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function require_role(array $roles): void {
    require_login();
    if (!in_array(user()['role'], $roles, true)) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function flash(string $message, string $type='success'): void {
    $_SESSION['flash'] = ['message'=>$message, 'type'=>$type];
}

function redirect(string $url='dashboard.php'): void {
    header('Location: '.$url);
    exit;
}

function csrf_field(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf" value="'.e($_SESSION['csrf']).'">';
}

function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD']==='POST' &&
        (!isset($_POST['csrf']) ||
        !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']))) {
        http_response_code(419);
        exit('Invalid form token. Refresh and try again.');
    }
}

function header_html(string $title): void {
    $u = user();
    $role = $u['role'] ?? '';

    echo '<!doctype html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>'.e($title).'</title>
    <link rel="stylesheet" href="assets/style.css">
    </head>
    <body>';

    if (logged_in()) {
        echo '<div class="app-layout">';

        echo '<aside class="sidebar">
            <div class="sidebar-brand">LabPortal</div>
            <div class="profile">
                <div class="profile-icon">👤</div>
                <strong>'.e($u['name'] ?? 'User').'</strong>
                <small>'.e(ucfirst($role)).'</small>
            </div>
            <nav class="side-nav">
                <a href="dashboard.php">▦ Dashboard</a>';

        if ($role === 'admin') {
            echo '
                <a href="staff.php">👥 Manage Staff</a>
                <a href="categories.php">▤ Test Categories</a>
                <a href="tests.php">⚗ Laboratory Tests</a>
                <a href="reports.php">▣ Generate Reports</a>';
        } elseif ($role === 'doctor') {
            echo '
                <a href="request_test.php">＋ Request a Test</a>
                <a href="reports.php">▣ View Reports</a>';
        } elseif ($role === 'technician') {
            echo '
                <a href="worklist.php">☷ Worklist</a>
                <a href="upload_report.php">⇧ Upload Report</a>';
        } else {
            echo '
                <a href="reports.php">▣ My Reports</a>';
        }

        echo '
                <a href="logout.php">↪ Logout</a>
            </nav>
        </aside>';

        echo '<section class="main-area">
            <header class="topbar">
                <strong>Laboratory Report Portal</strong>
                <span>'.e($u['name'] ?? 'User').' · '.e(ucfirst($role)).'</span>
            </header>
            <main class="page-content">';
    } else {
        echo '<main class="login-content">';
    }

    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert '.e($f['type']).'">'.e($f['message']).'</div>';
    }
}

function footer_html(): void {
    if (logged_in()) {
        echo '</main>
        <footer>Laboratory Report Portal · Academic Project</footer>
        </section>
        </div>';
    } else {
        echo '</main>';
    }

    echo '</body></html>';
}
