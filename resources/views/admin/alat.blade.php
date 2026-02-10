@extends('admin.layout')

@section('content')

<h2 class="page-title">Daftar Alat</h2>
<p class="page-subtitle">Kelola alat yang ingin ditambahkan</p>

<div class="card">

    <div class="header">
        <input id="search" class="search-input" placeholder="Cari alat...">
        <button class="btn" onclick="openModal()">+ Tambah Alat</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>FOTO</th>
                <th>NAMA ALAT</th>
                <th>KATEGORI</th>
                <th>DESKRIPSI</th>
                <th>HARGA / HARI</th>
                <th>STOK</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="table"></tbody>
    </table>
</div>

<!-- ===== MODAL ===== -->
<div id="modal" class="modal">
    <div class="modal-box">
        <h3 id="modalTitle">Tambah Alat</h3>

        <input type="hidden" id="alatId">

        <label>Nama Alat</label>
        <input id="alatNama">

        <label>Kategori</label>
        <select id="alatKategori"></select>

        <label>Deskripsi</label>
        <textarea id="alatDeskripsi" rows="3"></textarea>

        <label>Harga / Hari</label>
        <input id="alatHarga" type="number">

        <label>Stok</label>
        <input id="alatStok" type="number">

        <label>Foto</label>
        <input id="alatFoto" type="file">

        <div class="modal-action">
            <button class="btn-secondary" onclick="closeModal()">Batal</button>
            <button class="btn" onclick="simpan()">Simpan</button>
        </div>
    </div>
</div>

<style>
/* ===== PAGE ===== */
.page-title {
    font-size: 26px;
    font-weight: 600;
    margin-bottom: 4px;
    color: #1f2937;
}
.page-subtitle {
    margin-bottom: 20px;
    color: #6b7280;
}

/* ===== CARD ===== */
.card {
    background: #ffffff;
    padding: 24px;
    border-radius: 14px;
    box-shadow: 0 8px 22px rgba(0,0,0,.08);
}

/* ===== HEADER ===== */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}
.search-input {
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    min-width: 220px;
}

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
}
thead {
    background: #f3f4f6;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}
th {
    font-size: 13px;
    color: #6b7280;
}
td {
    color: #1f2937;
}
img.thumb {
    width: 46px;
    height: 46px;
    object-fit: cover;
    border-radius: 8px;
}

/* ===== MODAL ===== */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-box {
    background: #ffffff;
    padding: 26px;
    border-radius: 14px;
    width: 420px;
}
.modal-box h3 {
    margin-bottom: 14px;
    color: #1f2937;
}
.modal-box label {
    font-size: 14px;
    color: #374151;
}
.modal-box input,
.modal-box textarea,
.modal-box select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    margin-bottom: 14px;
}
.modal-action {
    text-align: right;
}

/* ===== BUTTON ===== */
.btn {
    background: #7b4ce2;
    color: #fff;
    border: none;
    padding: 9px 16px;
    border-radius: 8px;
    cursor: pointer;
}
.btn-secondary {
    background: #e5e7eb;
    border: none;
    padding: 9px 16px;
    border-radius: 8px;
    margin-right: 10px;
}

/* ===== DARK MODE (SAMA DENGAN KATEGORI) ===== */
body.dark .page-title { color: #f9fafb }
body.dark .page-subtitle { color: #cbd5f5 }

body.dark .card {
    background: #111827;
    box-shadow: 0 14px 32px rgba(0,0,0,.7);
}

body.dark thead {
    background: #1f2937;
}

body.dark th,
body.dark td {
    color: #f1f5f9;
    border-bottom: 1px solid #374151;
}

body.dark input,
body.dark textarea,
body.dark select {
    background: #1f2937;
    color: #f9fafb;
    border-color: #374151;
}

body.dark .modal-box {
    background: #111827;
}

body.dark .modal-box h3,
body.dark .modal-box label {
    color: #f9fafb;
}

body.dark .btn-secondary {
    background: #374151;
    color: #f9fafb;
}
</style>

<script>
const API_ALAT = "http://127.0.0.1:8000/api/alat";
const API_KATEGORI = "http://127.0.0.1:8000/api/kategori";
let dataAlat = [];

function load() {
    fetch(API_ALAT)
        .then(r => r.json())
        .then(r => {
            dataAlat = r.data || [];
            render(dataAlat);
        });
}

function loadKategori() {
    return fetch(API_KATEGORI)
        .then(r => r.json())
        .then(r => {
            alatKategori.innerHTML = `<option value="">-- Pilih --</option>`;
            r.data.forEach(k => {
                alatKategori.innerHTML += `<option value="${k.kategori_id}">${k.kategori_nama}</option>`;
            });
        });
}

function render(data) {
    table.innerHTML = data.map(a => `
        <tr>
            <td>#${a.alat_id}</td>
            <td>${a.photo_path ? `<img src="/storage/${a.photo_path}" class="thumb">` : '-'}</td>
            <td><b>${a.alat_nama}</b></td>
            <td>${a.kategori?.kategori_nama ?? '-'}</td>
            <td>${a.alat_deskripsi ?? '-'}</td>
            <td>Rp ${Number(a.alat_hargaperhari).toLocaleString()}</td>
            <td>${a.alat_stok}</td>
            <td>
                <button onclick="hapus(${a.alat_id})">🗑️</button>
            </td>
        </tr>
    `).join('');
}

search.addEventListener('keyup', () => {
    const key = search.value.toLowerCase();
    render(dataAlat.filter(a => a.alat_nama.toLowerCase().includes(key)));
});

function openModal() {
    modal.style.display = 'flex';
    loadKategori();
}
function closeModal() {
    modal.style.display = 'none';
}

function simpan() {
    let form = new FormData();
    form.append('alat_nama', alatNama.value);
    form.append('alat_kategori_id', alatKategori.value);
    form.append('alat_deskripsi', alatDeskripsi.value);
    form.append('alat_hargaperhari', alatHarga.value);
    form.append('alat_stok', alatStok.value);
    if (alatFoto.files[0]) form.append('photo', alatFoto.files[0]);

    fetch(API_ALAT, { method: 'POST', body: form })
        .then(() => { closeModal(); load(); });
}

function hapus(id) {
    if (!confirm('Hapus alat ini?')) return;
    fetch(API_ALAT + '/' + id, { method: 'DELETE' })
        .then(load);
}

load();
</script>

@endsection
