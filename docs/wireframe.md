# Wireframe & User Flow — SIMPUS-Mini

- **Nama:** Juan Madhy
- **NIM:** 254107060050
- **Mata Kuliah:** Desain dan Pemrograman Web
- **Sub-CPMK:** Merancang UI/UX aplikasi (proyek)

Halaman yang sudah ada (Beranda, Daftar/Tambah Buku, Daftar/Tambah Anggota — Jobsheet 1-3) belum mencakup fitur Login, Dashboard Petugas, dan Peminjaman/Pengembalian. Dokumen ini merancang wireframe untuk halaman-halaman tersebut sebelum diimplementasikan mulai Jobsheet 5 dan seterusnya.

---

## 1. Aktor & Otorisasi
- **Tamu**: hanya bisa melihat katalog buku (Beranda, Daftar Buku) tanpa login.
- **Petugas**: login untuk mengakses seluruh fitur CRUD dan transaksi peminjaman/pengembalian buku.

---

## 2. User Flow

### 2.1 User Flow — Peminjaman Buku
```
[Petugas Login] -> [Dashboard] -> [Pilih menu "Peminjaman Baru"]
        -> [Pilih Anggota] -> [Pilih Buku (stok > 0)]
        -> [Simpan] -> [Stok buku berkurang 1] -> [Kembali ke Dashboard]
```

### 2.2 User Flow — Pengembalian Buku
```
[Dashboard] -> [Menu "Pengembalian"] -> [Cari transaksi aktif (anggota/buku)]
        -> [Tandai "Dikembalikan"] -> [Stok buku bertambah 1]
        -> [Kembali ke Dashboard]
```

---

## 3. Wireframe Antarmuka (ASCII Design)

### 3.1 Halaman Login
```
+--------------------------------------+
|              SIMPUS-Mini             |
|--------------------------------------|
|                                      |
|          [ Login Petugas ]           |
|                                      |
|   Username : [______________]        |
|   Password : [______________]        |
|                                      |
|          [   Masuk   ]               |
|                                      |
|   Belum punya akun? Daftar di sini   |
+--------------------------------------+
```

### 3.2 Dashboard Petugas
```
+-------------------------------------------------------------------+
| SIMPUS-Mini   Beranda | Buku | Anggota | Peminjaman | (Petugas) Logout|
|-------------------------------------------------------------------|
|  [Total Buku]         [Total Anggota]        [Sedang Dipinjam]    |
|                                                                   |
|  Aksi Cepat:                                                      |
|  [ + Peminjaman Baru ]   [ + Pengembalian ]                       |
|                                                                   |
|  Transaksi Terbaru                                                |
|  --------------------------------------------------------------   |
|  Anggota        | Buku              | Tgl Pinjam | Status         |
|  Siti Aminah    | Laskar Pelangi    | 2026-09-01 | Dipinjam       |
|  Budi Santoso   | Bumi Manusia      | 2026-09-02 | Dipinjam       |
+-------------------------------------------------------------------+
```

### 3.3 Form Peminjaman
```
+--------------------------------------+
|  Form Peminjaman Buku                |
|--------------------------------------|
|  Anggota : [ dropdown pilih anggota ]|
|  Buku    : [ dropdown, hanya stok>0 ]|
|  Tanggal Pinjam : [ auto: hari ini ] |
|                                      |
|          [  Simpan Peminjaman  ]     |
+--------------------------------------+
```

### 3.4 Form Pengembalian
```
+--------------------------------------+
|  Pengembalian Buku                   |
|--------------------------------------|
|  Cari transaksi aktif:               |
|  [ nama anggota / judul buku ______ ]|
|                                      |
|  Anggota | Buku | Tgl Pinjam | Aksi  |
|  Siti A. | Laskar... | 01/09 | [Kembali] |
+--------------------------------------+
```

### 3.5 Riwayat Peminjaman per Anggota
```
+------------------------------------------------------------+
|  Riwayat Peminjaman — Siti Aminah (A001)                  |
|------------------------------------------------------------|
|  Buku                 | Pinjam     | Kembali    | Status   |
|  Laskar Pelangi       | 2026-08-01 | 2026-08-08 | Selesai  |
|  Bumi Manusia         | 2026-09-01 | -          | Dipinjam |
+------------------------------------------------------------+
```

---

## 4. Style Guide Visual & Mockup (Dark Mode Glassmorphism Theme)

Mengikuti konsistensi gaya desain visual yang telah dibangun dan disempurnakan pada Jobsheet 2 dan 3:

| Elemen Desain | Spesifikasi / Nilai | Kegunaan |
|---|---|---|
| **Background Utama** | `#000000` + CSS Grid Pattern | Latar belakang body dengan aksen grid subtle |
| **Container & Card** | `bg-white/[0.05] border-white/40 backdrop-blur-sm` | Efek glassmorphism untuk container form, tabel, & ringkasan |
| **Corner Radius** | `rounded-3xl` (`1.5rem` / `24px`) | Standar kelengkungan seluruh card, tabel, input, & tombol |
| **Warna Brand & Aksen Utama** | Emerald (`#34d399` / `#10b981`) | Logo SIMPUS, button submit/simpan, tombol aksi Edit, fokus input |
| **Warna Aksen Sekunder** | Orange (`#f97316` / `#ea580c`) | Aksen pemisah logo, hover menu navigasi, tombol aksi Hapus |
| **Teks Utama** | Stone 100 (`#f5f5f4`) | Heading dan konten teks utama |
| **Teks Muted / Label** | Neutral 300 / Neutral 400 | Label input form, info tanggal, teks footer |
| **Input & Select Field** | `bg-white/[0.05] border-neutral-700 rounded-3xl` | Input data dengan ring fokus warna emerald |
| **Tipografi** | `"SF Pro Display", -apple-system, BlinkMacSystemFont` | Font modern elegan untuk seluruh aplikasi |

---

## 5. Konsistensi Desain & Penanganan Kasus Khusus (Edge Cases)

1. **Konsistensi Navigasi**:
   - Seluruh halaman menggunakan layout header dan footer yang seragam.
   - Pada Jobsheet 10+, navbar akan dilengkapi indikator status login petugas beserta opsi Logout.
2. **Keterbacaan & Responsivitas**:
   - Wireframe dirancang agar dapat bertransisi mulus ke breakpoint mobile (hamburger menu & grid 1 kolom).
   - Tabel riwayat dan pengembalian dibungkus dengan wrapper `.table-responsive`.
3. **Edge Cases**:
   - **Buku Stok Kosong (Stok = 0)**: Dropdown pilihan buku pada form peminjaman harus memfilter atau mendisabel buku yang stoknya habis.
   - **Anggota Bertunggakan**: Validasi transaksi akan memeriksa status pinjaman aktif anggota sebelum menyetujui peminjaman baru.
