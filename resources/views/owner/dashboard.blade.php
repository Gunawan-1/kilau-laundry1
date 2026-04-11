@extends('adminlte::page')

@section('title', 'Dashboard Owner')

@section('content_header')
    <h1>Dashboard Admin</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-tshirt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pesanan Baru</span>
                    <span class="info-box-number">{{ $pesananBaru }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-sync-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Sedang Diproses</span>
                    <span class="info-box-number">{{ $pesananDiproses }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pendapatan Bulan Ini</span>
                    <span class="info-box-number">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $totalPelanggan }}</span>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Pendapatan 7 Hari Terakhir</h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Transaksi Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-3 pr-3">
                        @foreach($transaksiTerbaru as $t)
                        <li class="item d-flex align-items-center py-3 border-bottom">
                            <div class="mr-3 text-center d-flex align-items-center justify-content-center bg-primary rounded-circle shadow-sm" style="width: 45px; height: 45px; font-weight: bold; color: white; min-width: 45px;">
                                {{ strtoupper(substr($t->pelanggan->nama ?? 'N', 0, 2)) }}
                            </div>
                            <div class="flex-grow-1">
                                <span class="product-title text-dark font-weight-bold d-block">
                                    {{ $t->pelanggan->nama ?? 'N/A' }}
                                    <span class="float-right text-success small">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</span>
                                </span>
                                <span class="product-description text-muted small d-block">
                                    Invoice: {{ $t->kode_invoice }}
                                    <span class="badge {{ $t->status == 'Baru' ? 'badge-info' : 'badge-warning' }} float-right">{{ $t->status }}</span>
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('owner.transaksi.index') }}" class="uppercase text-sm">Lihat Semua Transaksi</a>
                </div>
            </div>
        </div>
    </div>

    
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Kam', 'Jum', 'Sab', 'Min', 'Sen', 'Sel', 'Rab'],
            datasets: [{
                label: 'Pendapatan',
                data: [650000, 400000, 300000, 280000, 100000, 80000, 150000],
                borderColor: '#3c8dbc',
                backgroundColor: 'rgba(60,141,188,0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@stop