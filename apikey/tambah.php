<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $quota = filter_var($_POST['quota'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $isActive = ($_POST['is_active'] ?? '') === '1';

    if ($name === '' || strlen($name) > 120 || $quota === false) {
        $error = 'Isi nama (maksimal 120 karakter) dan quota minimal 1.';
    } else {
        try {
            $apiKey = 'llm_' . bin2hex(random_bytes(24));
            $statement = db()->prepare(
                'INSERT INTO api_keys (name, api_key, is_active, quota, quota_usage)
                 VALUES (:name, :api_key, :is_active, :quota, :quota)'
            );
            $statement->execute(['name' => $name, 'api_key' => $apiKey, 'is_active' => $isActive, 'quota' => $quota]);
            set_flash('success', 'API key berhasil dibuat. Kamu bisa melihat dan menyalinnya dari daftar API keys.');
            redirect('apikey/list.php');
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $error = 'API key gagal disimpan. Periksa koneksi database.';
        }
    }
}

$pageTitle = 'Buat API key';
$activeNav = 'create';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-heading">
    <div><p class="eyebrow">API KEYS</p><h1>Buat API key</h1><p class="muted">Buat credential baru untuk gateway kamu.</p></div>
    <a class="button button-quiet" href="<?= e(app_url('apikey/list.php')) ?>">← Kembali ke daftar</a>
</section>
<section class="panel form-panel">
    <?php if ($error !== ''): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="stack-form">
        <?= csrf_field() ?>
        <div><label for="name">Nama API key</label><input id="name" name="name" type="text" maxlength="120" required placeholder="Contoh: Production App" value="<?= e($_POST['name'] ?? '') ?>"></div>
        <div class="form-row">
            <div><label for="quota">Quota</label><input id="quota" name="quota" type="number" min="1" step="1" required placeholder="100000" value="<?= e($_POST['quota'] ?? '') ?>"><small>Nilai quota usage demo akan dimulai dari angka yang sama.</small></div>
            <div><label for="is_active">Status awal</label><select id="is_active" name="is_active"><option value="1" <?= ($_POST['is_active'] ?? '1') === '1' ? 'selected' : '' ?>>Active</option><option value="0" <?= ($_POST['is_active'] ?? '') === '0' ? 'selected' : '' ?>>Inactive</option></select></div>
        </div>
        <p class="form-note">API key dibuat otomatis dengan generator acak yang aman. Simpan dan bagikan key dengan hati-hati.</p>
        <button class="button button-primary" type="submit">Buat API key <span aria-hidden="true">→</span></button>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
