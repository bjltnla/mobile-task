@extends('admin.layout')
@section('content')

<h3 class="page-title">Data Penyewaan</h3>
<p class="page-desc">Monitoring status penyewaan dan pembayaran</p>

<div class="card">
    <input id="search" placeholder="Cari penyewaan..." class="search">

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Tgl Sewa</th>
                <th>Total</th>
                <th>Status Bayar</th>
                <th>Status Kembali</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="table"></tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination" id="pagination"></div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div id="detailModal" class="modal">
    <div class="detail-box">
        <div class="modal-header">
            <h4>Detail Penyewaan</h4>
            <span onclick="closeDetail()">✕</span>
        </div>

        <div class="grid">
            <div class="info">
                <h5>Data Pelanggan</h5>
                <p>Nama: <b id="dNama"></b></p>
                <p>No Telp: <b id="dTelp"></b></p>
                <p>Alamat: <b id="dAlamat"></b></p>
            </div>

            <div class="info">
                <h5>Informasi Sewa</h5>
                <p>Tgl Sewa: <b id="dSewa"></b></p>
                <p>Tgl Kembali: <b id="dKembali"></b></p>
                <p>Status Bayar: <b id="dBayar"></b></p>
                <p>Status Kembali: <b id="dStatusKembali"></b></p>
            </div>
        </div>

        <h5 style="margin-top:14px">Rincian Alat</h5>
        <table>
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody id="detailItem"></tbody>
        </table>

        <div class="total">Total: <span id="dTotal"></span></div>
    </div>
</div>

<!-- ================= MODAL UPDATE STATUS ================= -->
<div id="statusModal" class="modal">
    <div class="status-box">
        <div class="modal-header">
            <h4>Update Status</h4>
            <span onclick="closeStatus()">✕</span>
        </div>

        <input type="hidden" id="statusId">

        <label>Status Pembayaran</label>
        <select id="statusBayar">
            <option value="lunas">Lunas</option>
            <option value="belum_lunas">Belum Lunas</option>
        </select>

        <label>Status Pengembalian</label>
        <select id="statusKembali">
            <option value="dikembalikan">Sudah Kembali</option>
            <option value="disewa">Disewa</option>
        </select>

        <div class="actions">
            <button class="btn-cancel" onclick="closeStatus()">Batal</button>
            <button class="btn-save" onclick="saveStatus()">Simpan</button>
        </div>
    </div>
</div>

<style>
.page-title{font-size:22px;font-weight:600}
.page-desc{color:#6b7280;margin-bottom:12px}
.card{background:#fff;padding:20px;border-radius:14px}
.search{padding:10px;border-radius:8px;border:1px solid #e5e7eb;width:240px}
table{width:100%;border-collapse:collapse;margin-top:14px}
th,td{padding:12px;border-bottom:1px solid #e5e7eb;font-size:14px}
th{color:#6b7280}
button{cursor:pointer;border:none;border-radius:8px;padding:6px 12px}
.btn-save{background:#2563eb;color:white}
.btn-cancel{background:#e5e7eb}
.modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);justify-content:center;align-items:center;z-index:999}
.detail-box{background:white;width:640px;padding:20px;border-radius:14px}
.status-box{background:white;width:380px;padding:20px;border-radius:14px}
.modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.info{background:#f9fafb;padding:12px;border-radius:10px}
select{width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;margin-bottom:12px}
.actions{display:flex;justify-content:flex-end;gap:8px}
.total{text-align:right;font-weight:600;margin-top:10px}

/* ================= PAGINATION ================= */
.pagination{
    display:flex;
    justify-content:center;
    gap:8px;
    margin-top:14px;
}
.page-btn{
    padding:6px 12px;
    border-radius:8px;
    border:1px solid #e5e7eb;
    cursor:pointer;
    background:white;
    font-size:14px;
}
.page-btn.active{
    background:#2563eb;
    color:white;
    border:none;
}
.page-btn:hover:not(.active){
    background:#f0f0f0;
}
</style>

<script>
/* ================= ELEMENT ================= */
const table = document.getElementById('table');
const detailModal = document.getElementById('detailModal');
const statusModal = document.getElementById('statusModal');
const pagination = document.getElementById('pagination');

const dNama = document.getElementById('dNama');
const dTelp = document.getElementById('dTelp');
const dAlamat = document.getElementById('dAlamat');
const dSewa = document.getElementById('dSewa');
const dKembali = document.getElementById('dKembali');
const dBayar = document.getElementById('dBayar');
const dStatusKembali = document.getElementById('dStatusKembali');
const detailItem = document.getElementById('detailItem');
const dTotal = document.getElementById('dTotal');

const statusId = document.getElementById('statusId');
const statusBayar = document.getElementById('statusBayar');
const statusKembali = document.getElementById('statusKembali');

/* ================= API ================= */
const API = "http://127.0.0.1:8000/api/penyewaan";
let dataPenyewaan = [];
let currentPage = 1;
let lastPage = 1;

/* ================= LOAD ================= */
function load(page = 1){
    fetch(`${API}?page=${page}`)
        .then(r => r.json())
        .then(r => {
            dataPenyewaan = r.data || [];
            render(dataPenyewaan);

            currentPage = r.pagination.current_page;
            lastPage = r.pagination.last_page;
            renderPagination();
        });
}

/* ================= RENDER TABLE ================= */
function render(data){
    table.innerHTML = data.map(p => `
        <tr>
            <td>#${p.penyewaan_id}</td>
            <td>${p.pelanggan?.pelanggan_nama ?? '-'}</td>
            <td>${p.penyewaan_tglsewa}</td>
            <td>Rp ${Number(p.penyewaan_totalharga).toLocaleString('id-ID')}</td>
            <td>${p.penyewaan_sttpembayaran === 'lunas'
                ? '<span style="color:green;font-weight:600">Lunas</span>'
                : '<span style="color:orange;font-weight:600">Belum</span>'}</td>
            <td>${p.penyewaan_sttkembali === 'dikembalikan'
                ? '<span style="color:blue;font-weight:600">Dikembalikan</span>'
                : '<span style="color:red;font-weight:600">Disewa</span>'}</td>
            <td>
                <button type="button" onclick="openDetail(${p.penyewaan_id})">Detail</button>
                <button type="button" onclick="openStatus(${p.penyewaan_id})">Update</button>
            </td>
        </tr>
    `).join('');
}

/* ================= PAGINATION ================= */
function renderPagination(){
    let html = '';

    if(currentPage > 1){
        html += `<button class="page-btn" onclick="load(${currentPage-1})">Prev</button>`;
    }

    for(let i = 1; i <= lastPage; i++){
        html += `<button class="page-btn ${i===currentPage?'active':''}" onclick="load(${i})">${i}</button>`;
    }

    if(currentPage < lastPage){
        html += `<button class="page-btn" onclick="load(${currentPage+1})">Next</button>`;
    }

    pagination.innerHTML = html;
}

/* ================= DETAIL MODAL ================= */
function openDetail(id){
    fetch(`${API}/${id}`)
        .then(r => r.json())
        .then(r => {
            const d = r.data;
            dNama.innerText   = d.pelanggan?.pelanggan_nama ?? '-';
            dTelp.innerText   = d.pelanggan?.pelanggan_telp ?? '-';
            dAlamat.innerText = d.pelanggan?.pelanggan_alamat ?? '-';
            dSewa.innerText    = d.penyewaan_tglsewa;
            dKembali.innerText = d.penyewaan_tglkembali ?? '-';
            dBayar.innerText   = d.penyewaan_sttpembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas';
            dStatusKembali.innerText = d.penyewaan_sttkembali === 'dikembalikan' ? 'Dikembalikan' : 'Disewa';

            detailItem.innerHTML = (d.detail || []).map(i => `
                <tr>
                    <td>${i.nama_alat}</td>
                    <td>${i.jumlah}</td>
                    <td>Rp ${Number(i.harga).toLocaleString('id-ID')}</td>
                    <td>Rp ${Number(i.total).toLocaleString('id-ID')}</td>
                </tr>
            `).join('');

            dTotal.innerText = 'Rp ' + Number(d.penyewaan_totalharga).toLocaleString('id-ID');
            detailModal.style.display = 'flex';
        });
}
function closeDetail(){ detailModal.style.display = 'none'; }

/* ================= UPDATE STATUS ================= */
function openStatus(id){
    const p = dataPenyewaan.find(x => x.penyewaan_id === id);
    statusId.value = id;
    statusBayar.value = p.penyewaan_sttpembayaran;
    statusKembali.value = p.penyewaan_sttkembali;
    statusModal.style.display = 'flex';
}
function closeStatus(){ statusModal.style.display = 'none'; }

function saveStatus(){
    fetch(`${API}/${statusId.value}/status`, {
        method:'PUT',
        headers:{ 'Content-Type':'application/json' },
        body:JSON.stringify({
            penyewaan_sttpembayaran: statusBayar.value,
            penyewaan_sttkembali: statusKembali.value
        })
    }).then(() => {
        closeStatus();
        load(currentPage);
    });
}

// load pertama kali
load();
</script>

@endsection
