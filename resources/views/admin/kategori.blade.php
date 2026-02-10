@extends('admin.layout')

@section('content')

<h2 class="page-title">Daftar Kategori</h2>
<p class="page-subtitle">Kelola kategori yang ingin ditambahkan</p>

<div class="card">

    <div class="header">
        <input id="search"
               class="search-input"
               placeholder="Cari kategori...">

        <!-- TOMBOL DISAMAKAN DENGAN ALAT -->
        <button class="btn btn-add" onclick="openModal()">+ Tambah Kategori</button>
    </div>

    <h4 class="section-title">Daftar Kategori</h4>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NAMA KATEGORI</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="table"></tbody>
    </table>
</div>

<!-- ===== MODAL ===== -->
<div id="modal" class="modal">
    <div class="modal-box">
        <h3 id="modalTitle">Tambah Kategori</h3>

        <label>Nama Kategori</label>
        <input id="namaKategori" placeholder="Contoh: Kamera">

        <div class="modal-action">
            <button class="btn-secondary" onclick="closeModal()">Batal</button>
            <button class="btn" onclick="simpan()">Simpan</button>
        </div>
    </div>
</div>

<style>
/* ================= PAGE ================= */
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

/* ================= CARD ================= */
.card {
    background: #ffffff;
    padding: 24px;
    border-radius: 14px;
    box-shadow: 0 8px 22px rgba(0,0,0,.08);
}

/* ================= HEADER ================= */
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

/* ===== TOMBOL TAMBAH (SAMA DENGAN ALAT) ===== */
.btn {
    background: #7b4ce2;
    color: #ffffff;
    border: none;
    padding: 9px 16px;
    border-radius: 8px;
    cursor: pointer;
}

.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    transition: all .2s ease;
}

.btn-add:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* ================= SECTION ================= */
.section-title {
    margin: 10px 0 14px;
    font-size: 16px;
    font-weight: 500;
    color: #374151;
}

/* ================= TABLE ================= */
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

.aksi button {
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 16px;
    margin-right: 6px;
}

/* ================= MODAL ================= */
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
    width: 360px;
}

.modal-box h3 {
    margin-bottom: 14px;
    color: #1f2937;
}

.modal-box label {
    font-size: 14px;
    color: #374151;
}

.modal-box input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    margin-bottom: 18px;
}

.modal-action {
    text-align: right;
}

.btn-secondary {
    background: #e5e7eb;
    border: none;
    padding: 9px 16px;
    border-radius: 8px;
    margin-right: 10px;
    cursor: pointer;
}

/* ================= DARK MODE ================= */
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

body.dark .search-input,
body.dark .modal-box input {
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
const API = "http://127.0.0.1:8000/api/kategori";
let kategori = [];
let editId = null;

function load() {
    fetch(API)
        .then(res => res.json())
        .then(res => {
            kategori = res.data || [];
            render(kategori);
        });
}

function render(data) {
    table.innerHTML = data.map(k => `
        <tr>
            <td>#${k.kategori_id}</td>
            <td><b>${k.kategori_nama}</b></td>
            <td class="aksi">
                <button onclick="edit(${k.kategori_id}, '${k.kategori_nama}')">✏️</button>
                <button onclick="hapus(${k.kategori_id})">🗑️</button>
            </td>
        </tr>
    `).join('');
}

search.addEventListener('keyup', () => {
    const key = search.value.toLowerCase();
    render(kategori.filter(k => k.kategori_nama.toLowerCase().includes(key)));
});

function openModal() {
    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
    namaKategori.value = '';
    modalTitle.innerText = 'Tambah Kategori';
    editId = null;
}

function simpan() {
    if (!namaKategori.value.trim()) return alert('Nama kategori wajib diisi');

    fetch(editId ? `${API}/${editId}` : API, {
        method: editId ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ kategori_nama: namaKategori.value })
    }).then(() => {
        closeModal();
        load();
    });
}

function edit(id, nama) {
    editId = id;
    namaKategori.value = nama;
    modalTitle.innerText = 'Edit Kategori';
    openModal();
}

function hapus(id) {
    if (!confirm('Hapus kategori ini?')) return;
    fetch(`${API}/${id}`, { method: 'DELETE' }).then(load);
}

load();
</script>

@endsection
