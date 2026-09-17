function initNavToggle() {
    const btn = document.getElementById("nav-toggle-btn");
    const nav = document.getElementById("mobile-nav");
    if (!btn || !nav) return;

    btn.addEventListener("click", () => {
        nav.classList.toggle("nav-open");
    });
}

function initHapusConfirm() {
    document.addEventListener("click", (e) => {
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent.trim() : "data ini";
        if (confirm(`Yakin ingin menghapus "${nama}"?`)) {
            row?.remove();
        }
    });
}

function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector("table");
    if (!input || !table) return;

    input.addEventListener("keyup", () => {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
}

function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error text-red-400 text-xs mt-1 pl-4 block";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    form.addEventListener("submit", (e) => {
        let valid = true;

        const judul = form.querySelector("[name='judul'], [name='nama']");
        if (judul && judul.value.trim() === "") {
            tampilkanError(judul, "Field ini wajib diisi.");
            valid = false;
        } else if (judul) {
            hapusError(judul);
        }

        const pengarang = form.querySelector("[name='pengarang']");
        if (pengarang && pengarang.value.trim() === "") {
            tampilkanError(pengarang, "Pengarang wajib diisi.");
            valid = false;
        } else if (pengarang) {
            hapusError(pengarang);
        }

        const noAnggota = form.querySelector("[name='no_anggota']");
        if (noAnggota && noAnggota.value.trim() === "") {
            tampilkanError(noAnggota, "No. Anggota wajib diisi.");
            valid = false;
        } else if (noAnggota) {
            hapusError(noAnggota);
        }

        const tahun = form.querySelector("[name='tahun']");
        if (tahun) {
            const val = parseInt(tahun.value, 10);
            if (isNaN(val) || val < 1900 || val > 2026) {
                tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
                valid = false;
            } else {
                hapusError(tahun);
            }
        }

        const stok = form.querySelector("[name='stok']");
        if (stok) {
            const val = parseInt(stok.value, 10);
            if (isNaN(val) || val < 0) {
                tampilkanError(stok, "Stok tidak boleh negatif.");
                valid = false;
            } else {
                hapusError(stok);
            }
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});
