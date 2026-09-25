# Wireframe JuanRouter

## Halaman

- `login.php`: autentikasi admin.
- `index.php`: total API key, status active/inactive, quota, dan daftar key terbaru.
- `apikey/list.php`: daftar, cari, tampil/sembunyikan, salin, edit, toggle status, dan hapus API key.
- `apikey/tambah.php`: membuat API key dengan quota dan status awal.

## Alur data

```text
login.php → session → index.php
                         ├─ apikey/list.php
                         └─ apikey/tambah.php → PostgreSQL
```
