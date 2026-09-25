<?php
require_once __DIR__ . '/bootstrap.php';
$pageTitle = $pageTitle ?? 'Dashboard';
$activeNav = $activeNav ?? '';
$flash = take_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | JuanRouter</title>
    <link rel="stylesheet" href="<?= e(app_url('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(app_url('assets/css/admin.css')) ?>">
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= e(app_url('index.php')) ?>">
        <span>Juan<span class="brand-accent">/</span>Router</span>
    </a>
    <button class="menu-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="main-nav">
        <span></span><span></span><span></span>
    </button>
    <nav class="main-nav" id="main-nav" aria-label="Navigasi utama">
        <a class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>" href="<?= e(app_url('index.php')) ?>">Dashboard</a>
        <a class="<?= $activeNav === 'keys' ? 'active' : '' ?>" href="<?= e(app_url('apikey/list.php')) ?>">API Keys</a>
        <a class="<?= $activeNav === 'create' ? 'active' : '' ?>" href="<?= e(app_url('apikey/tambah.php')) ?>">Buat API Key</a>
    </nav>
    <div class="account-area">
        <span class="account-name"><?= e($_SESSION['username'] ?? 'Admin') ?></span>
        <form method="post" action="<?= e(app_url('logout.php')) ?>" class="logout-form">
            <?= csrf_field() ?>
            <button class="button button-quiet button-small" type="submit">Keluar</button>
        </form>
    </div>
</header>
<main class="page-shell">
    <?php if ($flash): ?>
        <div class="notice notice-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
    <?php endif; ?>
