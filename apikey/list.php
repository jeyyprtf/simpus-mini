<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $action = $_POST['action'] ?? '';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($id === false) {
        set_flash('error', 'API key tidak ditemukan.');
        redirect('apikey/list.php');
    }

    try {
        $pdo = db();
        if ($action === 'toggle') {
            $statement = $pdo->prepare('UPDATE api_keys SET is_active = NOT is_active WHERE id = :id');
            $statement->execute(['id' => $id]);
            set_flash('success', $statement->rowCount() ? 'Status API key berhasil diubah.' : 'API key tidak ditemukan.');
        } elseif ($action === 'update') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $quota = filter_var($_POST['quota'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $isActive = ($_POST['is_active'] ?? '') === '1';
            if ($name === '' || strlen($name) > 120 || $quota === false) {
                set_flash('error', 'Nama wajib diisi (maksimal 120 karakter) dan quota minimal 1.');
            } else {
                $statement = $pdo->prepare('UPDATE api_keys SET name = :name, quota = :quota, quota_usage = :quota, is_active = :is_active WHERE id = :id');
                $statement->execute(['name' => $name, 'quota' => $quota, 'is_active' => $isActive, 'id' => $id]);
                set_flash('success', 'Detail API key berhasil diperbarui.');
            }
        } elseif ($action === 'delete') {
            $statement = $pdo->prepare('DELETE FROM api_keys WHERE id = :id');
            $statement->execute(['id' => $id]);
            set_flash('success', $statement->rowCount() ? 'API key berhasil dihapus.' : 'API key tidak ditemukan.');
        } else {
            set_flash('error', 'Aksi tidak dikenali.');
        }
    } catch (Throwable $exception) {
        error_log($exception->getMessage());
        set_flash('error', 'Perubahan gagal disimpan. Periksa koneksi database.');
    }
    redirect('apikey/list.php');
}

$keys = db()->query('SELECT id, name, api_key, is_active, quota, quota_usage, created_at FROM api_keys ORDER BY created_at DESC, id DESC')->fetchAll();
$pageTitle = 'API keys';
$activeNav = 'keys';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-heading">
    <div><p class="eyebrow">MANAGEMENT</p><h1>API keys</h1><p class="muted">Kelola credential, quota, dan status akses.</p></div>
    <a class="button button-primary" href="<?= e(app_url('apikey/tambah.php')) ?>"><span aria-hidden="true">＋</span> Buat API key</a>
</section>
<section class="panel">
    <div class="toolbar"><div><h2>Semua API keys</h2><p class="muted"><?= count($keys) ?> key terdaftar</p></div><label class="search-field"><span aria-hidden="true">⌕</span><input id="key-search" type="search" placeholder="Cari nama atau status..." aria-label="Cari API key"></label></div>
    <?php if (!$keys): ?>
        <div class="empty-state"><p>Belum ada API key.</p><a class="text-link" href="<?= e(app_url('apikey/tambah.php')) ?>">Buat API key pertama kamu →</a></div>
    <?php else: ?>
        <div class="table-wrap"><table class="data-table key-table"><thead><tr><th>Nama &amp; key</th><th>Status</th><th>Quota</th><th>Dibuat</th><th>Aksi</th></tr></thead><tbody>
        <?php foreach ($keys as $key): ?>
            <tr class="key-row" data-search="<?= e(strtolower($key['name'] . ' ' . ($key['is_active'] ? 'active' : 'inactive'))) ?>">
                <td class="key-cell"><strong class="strong-cell"><?= e($key['name']) ?></strong><div class="secret-line"><code class="secret-mask" data-secret="<?= e($key['api_key']) ?>">••••••••••••••••••••••••</code><button class="icon-button reveal-key" type="button" aria-label="Tampilkan API key" aria-pressed="false">◉</button><button class="icon-button copy-key" type="button" aria-label="Salin API key">⧉</button></div></td>
                <td><span class="badge <?= $key['is_active'] ? 'badge-active' : 'badge-inactive' ?>"><?= $key['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                <td><strong><?= number_format((int) $key['quota_usage']) ?></strong><span class="quota-total"> / <?= number_format((int) $key['quota']) ?></span></td>
                <td><?= e(date('d M Y', strtotime($key['created_at']))) ?></td>
                <td><div class="action-stack">
                    <form method="post" class="inline-form">
                        <?= csrf_field() ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= e($key['id']) ?>">
                        <button class="button button-small <?= $key['is_active'] ? 'button-warn' : 'button-quiet' ?>" type="submit"><?= $key['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                    </form>
                    <details class="edit-details"><summary>Edit</summary>
                        <form method="post" class="edit-form">
                            <?= csrf_field() ?><input type="hidden" name="action" value="update"><input type="hidden" name="id" value="<?= e($key['id']) ?>">
                            <label>Nama<input type="text" name="name" maxlength="120" required value="<?= e($key['name']) ?>"></label>
                            <label>Quota<input type="number" name="quota" min="1" required value="<?= e($key['quota']) ?>"></label>
                            <label>Status<select name="is_active"><option value="1" <?= $key['is_active'] ? 'selected' : '' ?>>Active</option><option value="0" <?= !$key['is_active'] ? 'selected' : '' ?>>Inactive</option></select></label>
                            <button class="button button-primary button-small" type="submit">Simpan perubahan</button>
                        </form>
                    </details>
                    <form method="post" class="inline-form delete-form">
                        <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= e($key['id']) ?>">
                        <button class="button button-delete button-small" type="submit">Hapus</button>
                    </form>
                </div></td>
            </tr>
        <?php endforeach; ?>
        </tbody></table></div>
    <?php endif; ?>
</section>
<script src="<?= e(app_url('assets/js/apikey.js')) ?>" defer></script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
