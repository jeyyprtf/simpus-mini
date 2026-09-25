<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/database.php';

if (!empty($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    try {
        $statement = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = :username');
        $statement->execute(['username' => $username]);
        $user = $statement->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            unset($_SESSION['csrf_token']);
            redirect('index.php');
        }
        $error = 'Username atau password salah.';
    } catch (Throwable $exception) {
        error_log($exception->getMessage());
        $error = 'Database belum siap. Periksa koneksi PostgreSQL dan ekstensi pdo_pgsql.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | JuanRouter</title>
    <link rel="stylesheet" href="<?= e(app_url('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(app_url('assets/css/admin.css')) ?>">
</head>
<body class="login-page">
<main class="login-card">
    <a class="brand login-brand" href="<?= e(app_url('login.php')) ?>">
        <span>Juan<span class="brand-accent">/</span>Router</span>
    </a>
    <p class="eyebrow">ADMIN CONSOLE</p>
    <h1>Selamat datang</h1>
    <p class="muted">Masuk untuk mengelola API key gateway kamu.</p>
    <?php if ($error !== ''): ?>
        <div class="notice notice-error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="post" class="stack-form">
        <?= csrf_field() ?>
        <label for="username">Username</label>
        <input id="username" name="username" type="text" autocomplete="username" required autofocus value="<?= e($_POST['username'] ?? '') ?>">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <button class="button button-primary button-full" type="submit">Masuk ke dashboard <span aria-hidden="true">→</span></button>
    </form>
    <p class="login-hint">Demo lokal: <code>admin</code> / <code>admin123</code></p>
</main>
</body>
</html>
