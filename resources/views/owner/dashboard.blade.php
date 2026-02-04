@extends('adminlte::page')

@section('title', 'Dashboard Owner')

@section('content_header')
    <h1>Dashboard Owner</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pendapatan Hari Ini</span>
                    <span class="info-box-number">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-green elevation-1"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pendapatan Bulan Ini</span>
                    <span class="info-box-number">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Transaksi</span>
                    <span class="info-box-number">{{ $totalTransaksi }}</span>
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
        {{-- Grafik Pendapatan --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pendapatan Bulan Ini</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="grafikPendapatan" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Layanan --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Layanan Terlaris</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($layananTerlaris as $layanan)
                            <tr>
                                <td>{{ $layanan->nama_layanan }}</td>
                                <td><span class="badge badge-success">{{ $layanan->total }} Transaksi</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">Belum ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('grafikPendapatan').getContext('2d');
        
        // Membuat gradient untuk background bawah grafik (opsional agar lebih mewah)
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(40, 167, 69, 0.5)');
        gradient.addColorStop(1, 'rgba(40, 167, 69, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($grafikPendapatan->pluck('tanggal')),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($grafikPendapatan->pluck('total')),
                    fill: true,
                    backgroundColor: gradient, // Menggunakan gradient
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(40, 167, 69, 1)',
                    tension: 0.4, // INI KUNCINYA agar grafik berbentuk gelombang/smooth
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan label dataset jika ingin lebih clean
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
