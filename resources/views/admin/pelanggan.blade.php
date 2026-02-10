@extends('admin.layout')

@section('content')

<h2 class="page-title">Daftar Pelanggan</h2>
<p class="page-subtitle">Kelola data pelanggan yang ingin ditambahkan</p>

<div class="card">
    <div class="header">
        <input id="search" class="search-input" placeholder="Cari pelanggan...">
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>FOTO</th>
                <th>NAMA</th>
                <th>EMAIL</th>
                <th>TELEPON</th>
                <th>ALAMAT</th>
            </tr>
        </thead>
        <tbody id="table"></tbody>
    </table>
</div>

<style>
/* ===== PAGE ===== */
.page-title { font-size:26px; font-weight:600; margin-bottom:4px; color:#1f2937; }
.page-subtitle { margin-bottom:20px; color:#6b7280; }

/* ===== CARD ===== */
.card { background:#fff; padding:24px; border-radius:14px; box-shadow:0 8px 22px rgba(0,0,0,.08); }

/* ===== HEADER ===== */
.header { display:flex; justify-content:flex-start; align-items:center; margin-bottom:16px; }
.search-input { padding:10px 12px; border-radius:8px; border:1px solid #d1d5db; min-width:220px; }

/* ===== TABLE ===== */
table { width:100%; border-collapse: collapse; }
thead { background:#f3f4f6; }
th, td { padding:12px; border-bottom:1px solid #e5e7eb; text-align:left; }
th { font-size:13px; color:#6b7280; }
td { color:#1f2937; }
img.thumb { width:46px; height:46px; object-fit:cover; border-radius:8px; }

/* ===== DARK MODE ===== */
body.dark .page-title { color:#f9fafb }
body.dark .page-subtitle { color:#cbd5f5 }
body.dark .card { background:#111827; box-shadow:0 14px 32px rgba(0,0,0,.7); }
body.dark thead { background:#1f2937; }
body.dark th, body.dark td { color:#f1f5f9; border-bottom:1px solid #374151; }
body.dark input { background:#1f2937; color:#f9fafb; border-color:#374151; }
</style>

<script>
const API_PELANGGAN = "{{ url('/api/pelanggan') }}";
let dataPelanggan = [];

// Load data pelanggan
function load() {
    fetch(API_PELANGGAN)
        .then(r => r.json())
        .then(r => {
            dataPelanggan = r.data || [];
            render(dataPelanggan);
        });
}

// Render tabel
function render(data) {
    table.innerHTML = data.map(p => `
        <tr>
            <td>#${p.pelanggan_id}</td>
            <td>${p.photo_path ? `<img src="/storage/${p.photo_path}" class="thumb">` : '-'}</td>
            <td>${p.pelanggan_nama}</td>
            <td>${p.pelanggan_email}</td>
            <td>${p.pelanggan_notelp}</td>
            <td>${p.pelanggan_alamat}</td>
        </tr>
    `).join('');
}

// Search filter
search.addEventListener('keyup', () => {
    const key = search.value.toLowerCase();
    render(dataPelanggan.filter(p => p.pelanggan_nama.toLowerCase().includes(key)));
});

// Jalankan load pertama kali
load();
</script>

@endsection
