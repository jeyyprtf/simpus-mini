# Jobsheet 6 — Fetch API & JSON

- **Nama:** Juan Madhy
- **NIM:** 254107060050
- **Mata Kuliah:** Desain dan Pemrograman Web

Sub-CPMK: Menerapkan komunikasi asinkron (AJAX/fetch, JSON).

## Deskripsi Jobsheet
Pada Jobsheet 6 ini, data pada tabel buku dan anggota tidak lagi di-hardcode dalam file HTML, melainkan diambil secara asinkron dari file JSON lokal (`data/buku.json` dan `data/anggota.json`) menggunakan Fetch API dan pola `async/await`.

## Perubahan dari Jobsheet 5
- Menambahkan data JSON lokal: `data/buku.json` dan `data/anggota.json`.
- `buku/list.html` & `anggota/list.html`: Elemen `<tbody>` dikosongkan dan baris tabel dirender secara dinamis oleh `assets/js/buku.js` dan `assets/js/anggota.js`.
- Menambahkan indikator loading (`#loading-indicator`) dengan simulasi delay jaringan agar proses asinkron terlihat.
- Menambahkan penanganan error (`try/catch`) yang menampilkan pesan peringatan di tabel jika data gagal dimuat.
- Memperbarui `initHapusConfirm` di `app.js` menggunakan event delegation (`document.addEventListener("click", ...)`) agar tetap berfungsi pada baris tabel yang dimuat secara dinamis.

## Struktur Folder
```
jobsheet-06/
├── index.html
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── fonts/
│   └── js/
│       ├── anggota.js
│       ├── app.js
│       └── buku.js
├── buku/
│   ├── list.html
│   └── tambah.html
├── anggota/
│   ├── list.html
│   └── tambah.html
├── data/
│   ├── anggota.json
│   └── buku.json
├── docs/
│   └── wireframe.md
├── Infografis.png
└── README.md
```

## Cara Menjalankan
Karena peramban membatasi permintaan `fetch()` ke protokol file lokal (`file://`), jalankan proyek ini melalui web server lokal:
```bash
python3 -m http.server 8000
```
atau menggunakan ekstensi Live Server pada VS Code, lalu akses `http://localhost:8000/index.html`.
