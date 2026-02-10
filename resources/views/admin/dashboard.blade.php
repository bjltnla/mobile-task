@extends('admin.layout')

@section('content')

<h2 class="page-title">Dashboard</h2>
<p class="page-subtitle">Ringkasan data alat dan penyewaan</p>

<div class="dashboard">

    <!-- ===== CARDS ===== -->
    <div class="cards">
        <div class="card">
            <h4>Total Alat</h4>
            <p id="totalAlat">0</p>
        </div>
        <div class="card">
            <h4>Total Stok</h4>
            <p id="totalStok">0</p>
        </div>
        <div class="card">
            <h4>Total Penyewaan</h4>
            <p id="totalSewa">0</p>
        </div>
    </div>

    <!-- ===== CHART ===== -->
    <div class="card chart-card">
        <h4>Grafik Penyewaan per Bulan</h4>
        <canvas id="chartPenyewaan"></canvas>
    </div>

</div>

<style>
/* ===== LAYOUT ===== */
.dashboard {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

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

/* ===== CARDS ===== */
.cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.card {
    background: #ffffff;
    padding: 22px;
    border-radius: 14px;
    box-shadow: 0 8px 22px rgba(0,0,0,.08);
    flex: 1;
    min-width: 200px;
    transition: .25s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card h4 {
    font-size: 15px;
    color: #374151;
    margin-bottom: 10px;
}

.card p {
    font-size: 30px;
    font-weight: 600;
    color: #4f46e5;
}

/* ===== CHART ===== */
.chart-card {
    padding: 26px;
}

.chart-card h4 {
    margin-bottom: 16px;
    font-size: 16px;
    color: #374151;
}

.chart-card canvas {
    height: 340px !important;
}

/* ===== DARK MODE ===== */
body.dark .page-title,
body.dark .chart-card h4 {
    color: #f9fafb;
}

body.dark .page-subtitle {
    color: #cbd5f5;
}

body.dark .card {
    background: #111827;
    box-shadow: 0 14px 32px rgba(0,0,0,.7);
}

body.dark .card h4 {
    color: #f9fafb;
}

body.dark .card p {
    color: #a5b4fc;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const API_ALAT = "http://127.0.0.1:8000/api/alat";
const API_SEWA = "http://127.0.0.1:8000/api/penyewaan";

/* ===== LOAD CARD ===== */
async function loadCards() {
    const alat = await fetch(API_ALAT).then(r => r.json());
    const sewa = await fetch(API_SEWA).then(r => r.json());

    let stok = 0;
    (alat.data || []).forEach(a => stok += Number(a.alat_stok || 0));

    document.getElementById('totalAlat').innerText = alat.data?.length || 0;
    document.getElementById('totalStok').innerText = stok;
    document.getElementById('totalSewa').innerText = sewa.data?.length || 0;
}

/* ===== LINE CHART ===== */
async function loadChart() {
    const res = await fetch(API_SEWA).then(r => r.json());
    const data = res.data || [];

    /**
     * FORMAT:
     * {
     *   2024: [0,1,3,0...],
     *   2025: [1,2,4,0...]
     * }
     */
    const yearly = {};

    data.forEach(item => {
        if (!item.penyewaan_tglsewa) return;

        const d = new Date(item.penyewaan_tglsewa);
        const year = d.getFullYear();
        const month = d.getMonth(); // 0-11

        if (!yearly[year]) {
            yearly[year] = Array(12).fill(0);
        }
        yearly[year][month]++;
    });

    const labels = [
        'Jan','Feb','Mar','Apr','Mei','Jun',
        'Jul','Agu','Sep','Okt','Nov','Des'
    ];

    const colors = ['#6366f1','#f59e0b','#10b981','#ef4444'];

    const datasets = Object.keys(yearly).map((year, i) => ({
        label: year,
        data: yearly[year],
        borderColor: colors[i % colors.length],
        borderWidth: 3,
        tension: 0.45,
        pointRadius: 4,
        pointHoverRadius: 6,
        fill: false
    }));

    new Chart(document.getElementById('chartPenyewaan'), {
        type: 'line',
        data: { labels, datasets },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                x: { grid: { display: false }},
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}

/* ===== INIT ===== */
loadCards();
loadChart();
</script>

@endsection
