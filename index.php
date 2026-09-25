<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/database.php';

$pdo = db();
$stats = $pdo->query(
    'SELECT COUNT(*) AS total,
            COUNT(*) FILTER (WHERE is_active) AS active,
            COUNT(*) FILTER (WHERE NOT is_active) AS inactive,
            COALESCE(SUM(quota), 0) AS quota,
            COALESCE(SUM(quota_usage), 0) AS usage
     FROM api_keys'
)->fetch();
$recentKeys = $pdo->query('SELECT id, name, is_active, quota, created_at FROM api_keys ORDER BY created_at DESC, id DESC LIMIT 5')->fetchAll();

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
require __DIR__ . '/includes/header.php';
?>
<section class="page-heading">
    <div><p class="eyebrow">OVERVIEW</p><h1>Dashboard</h1><p class="muted">Ringkasan pengelolaan API key kamu.</p></div>
    <a class="button button-primary" href="<?= e(app_url('apikey/tambah.php')) ?>"><span aria-hidden="true">＋</span> Buat API key</a>
</section>

<section class="stat-grid" aria-label="Ringkasan API key">
    <article class="stat-card"><span class="stat-icon">⌘</span><span class="stat-label">Total API keys</span><strong><?= e($stats['total']) ?></strong><span class="stat-note">Semua key terdaftar</span></article>
    <article class="stat-card"><span class="stat-icon status-dot-on">●</span><span class="stat-label">Active</span><strong><?= e($stats['active']) ?></strong><span class="stat-note">Siap digunakan</span></article>
    <article class="stat-card"><span class="stat-icon status-dot-off">●</span><span class="stat-label">Inactive</span><strong><?= e($stats['inactive']) ?></strong><span class="stat-note">Akses dinonaktifkan</span></article>
    <article class="stat-card"><span class="stat-icon">◷</span><span class="stat-label">Total quota</span><strong><?= number_format((int) $stats['quota']) ?></strong><span class="stat-note">Penggunaan demo: <?= number_format((int) $stats['usage']) ?></span></article>
</section>

<section class="panel">
    <div class="panel-heading"><div><p class="eyebrow">TERBARU</p><h2>API key terbaru</h2></div><a class="text-link" href="<?= e(app_url('apikey/list.php')) ?>">Lihat semua <span aria-hidden="true">→</span></a></div>
    <?php if (!$recentKeys): ?>
        <div class="empty-state"><p>Belum ada API key.</p><a class="text-link" href="<?= e(app_url('apikey/tambah.php')) ?>">Buat API key pertama kamu →</a></div>
    <?php else: ?>
        <div class="table-wrap"><table class="data-table"><thead><tr><th>Nama</th><th>Dibuat</th><th>Quota</th><th>Status</th></tr></thead><tbody>
        <?php foreach ($recentKeys as $key): ?>
            <tr><td class="strong-cell"><?= e($key['name']) ?></td><td><?= e(date('d M Y', strtotime($key['created_at']))) ?></td><td><?= number_format((int) $key['quota']) ?></td><td><span class="badge <?= $key['is_active'] ? 'badge-active' : 'badge-inactive' ?>"><?= $key['is_active'] ? 'Active' : 'Inactive' ?></span></td></tr>
        <?php endforeach; ?>
        </tbody></table></div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
