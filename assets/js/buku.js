async function muatDaftarBuku() {
    const tbody = document.querySelector("table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return;

    if (loading) loading.classList.remove("hidden");
    tbody.innerHTML = "";

    try {
        await new Promise((resolve) => setTimeout(resolve, 600));

        const res = await fetch("../data/buku.json");
        if (!res.ok) {
            throw new Error(`Status ${res.status}`);
        }
        const daftarBuku = await res.json();

        daftarBuku.forEach((buku) => {
            const tr = document.createElement("tr");
            tr.className = "divide-x divide-neutral-700 divide-x-2";
            tr.innerHTML = `
                <td class="p-2 md:px-4 md:py-4">${buku.judul}</td>
                <td class="p-2 md:px-4 md:py-4">${buku.pengarang}</td>
                <td class="p-2 md:px-4 md:py-4">${buku.tahun}</td>
                <td class="p-2 md:px-4 md:py-4">${buku.stok}</td>
                <td class="p-2 md:px-4 md:py-4 flex gap-2 md:gap-4 flex-col md:flex-row justify-between font-bold">
                    <button type="button" class="btn-edit bg-emerald-500 hover:bg-emerald-700 text-white px-3 py-2 md:py-1 md:px-5 rounded-3xl md:w-full">Edit</button>
                    <button type="button" class="btn-delete btn-hapus bg-orange-500 hover:bg-orange-700 text-white px-3 py-2 md:py-1 md:px-5 rounded-3xl md:w-full">Hapus</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400 font-semibold">Gagal memuat data: ${err.message}</td></tr>`;
    } finally {
        if (loading) loading.classList.add("hidden");
    }
}

document.addEventListener("DOMContentLoaded", muatDaftarBuku);
