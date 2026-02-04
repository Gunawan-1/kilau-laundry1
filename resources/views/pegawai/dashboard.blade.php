@extends('adminlte::page')

@section('title', 'Dashboard Pegawai')

@section('content_header')
    <h1>Dashboard Pegawai</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Info boxes -->
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
    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">5 Transaksi Terakhir</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Tgl Masuk</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksiTerbaru as $transaksi)
                                    <tr>
                                        <td><a href="{{ route('admin.transaksi.show', $transaksi->id) }}">{{ $transaksi->kode_invoice }}</a></td>
                                        <td>{{ $transaksi->pelanggan->nama ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d M Y') }}</td>
                                        <td>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                                        <td><span class="badge {{ $transaksi->status == 'Baru' ? 'badge-info' : ($transaksi->status == 'Proses' ? 'badge-warning' : ($transaksi->status == 'Selesai' ? 'badge-primary' : 'badge-success')) }}">{{ $transaksi->status }}</span></td>
                                        <td><span class="badge {{ $transaksi->status_pembayaran == 'Lunas' ? 'badge-success' : 'badge-danger' }}">{{ $transaksi->status_pembayaran }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
