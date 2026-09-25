# JuanRouter

Project Jobsheet 7/8: aplikasi admin untuk mengelola API key gateway AI menggunakan PHP native dan PostgreSQL.

## Fitur

- Login admin berbasis session dan password hash.
- Dashboard ringkasan API key.
- CRUD API key: tambah, lihat, edit, hapus.
- Toggle status active/inactive.
- Toggle tampil/sembunyikan API key dan tombol copy.
- Validasi server-side, prepared statement PDO, dan CSRF token.

## Kebutuhan

- PHP 8.1+ dengan ekstensi `pdo_pgsql`.
- PostgreSQL 13+.

## Setup database lokal

```bash
createdb llm_gateway
psql -d llm_gateway -f sql/schema.sql
```

Pastikan ekstensi PostgreSQL aktif:

```bash
php -m | grep -E 'PDO|pgsql'
```

Output harus mencantumkan `PDO` dan `pdo_pgsql`. Di Ubuntu, pasang paket yang cocok dengan versi PHP, misalnya `sudo apt install php-pgsql`, lalu restart PHP/Apache.

Untuk PostgreSQL lokal dengan peer authentication:

```bash
export DB_NAME=llm_gateway
export DB_USER="$(whoami)"
unset DB_PASSWORD DB_HOST DB_PORT
```

Untuk koneksi TCP (umum di VPS), atur semua nilai lewat environment service, khususnya password database yang kuat:

```text
DB_NAME=llm_gateway
DB_USER=llm_gateway_app
DB_PASSWORD=<password-kuat>
DB_HOST=127.0.0.1
DB_PORT=5432
```

Jalankan server dari folder project:

```bash
php -S localhost:8000
```

Buka <http://localhost:8000/login.php>.

Login demo dari schema: `admin` / `admin123`. Ganti kredensial demo sebelum aplikasi dibuka ke jaringan publik.

## Deployment VPS

Commit source code dan `sql/schema.sql`, lalu buat database PostgreSQL beserta user khusus aplikasi di VPS:

```bash
sudo -u postgres createuser --pwprompt llm_gateway_app
sudo -u postgres createdb --owner=llm_gateway_app llm_gateway
psql -h 127.0.0.1 -U llm_gateway_app -d llm_gateway -f sql/schema.sql
```

Set environment variable database pada service PHP/Apache atau konfigurasi deployment. PHP native tidak membaca `.env` otomatis. Jangan commit password database atau file `.env` ke repository. Cloudflare Tunnel hanya meneruskan trafik web ke aplikasi; tunnel tidak menggantikan database atau pengaturan kredensial.

Catatan: quota usage dan API key contoh hanya data demo. Aplikasi ini belum meneruskan request ke provider AI atau memberlakukan quota pada trafik API.
