@extends('adminlte::page')

@section('title', 'Dashboard Pelanggan')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Dashboard Pelanggan</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('pelanggan.pesanan.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Buat Pesanan Baru
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Info Row with Statistics -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $transaksiBerjalan->count() }}</h3>
                    <p>Pesanan Aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>
                        @php
                            $lunas = $transaksiBerjalan->where('status_pembayaran', 'Lunas')->count();
                        @endphp
                        {{ $lunas }}
                    </h3>
                    <p>Pembayaran Lunas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>
                        @php
                            $belumLunas = $transaksiBerjalan->where('status_pembayaran', 'Belum Lunas')->count();
                        @endphp
                        {{ $belumLunas }}
                    </h3>
                    <p>Menunggu Pembayaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($transaksiBerjalan->sum('total_bayar'), 0, ',', '.') }}</h3>
                    <p>Total Pengeluaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesanan Aktif Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-ul"></i> Laundry Aktif Anda
                    </h3>
                </div>
                <div class="card-body p-0">
                    @forelse ($transaksiBerjalan as $transaksi)
                        <div class="mailbox-read-message border-bottom p-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="text-muted small"><strong>Invoice</strong></label>
                                        <h5 class="font-weight-bold">{{ $transaksi->kode_invoice }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="text-muted small"><strong>Tanggal Masuk</strong></label>
                                        <p>{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="text-muted small"><strong>Total Bayar</strong></label>
                                        <h6 class="text-success font-weight-bold">Rp {{ number_format($transaksi->total_bayar) }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-muted small"><strong>Status</strong></label>
                                        <div>
                                            <span class="badge badge-@if($transaksi->status == 'Baru') primary @elseif($transaksi->status == 'Proses') warning @elseif($transaksi->status == 'Selesai') info @else success @endif">
                                                <i class="fas @if($transaksi->status == 'Baru') fa-inbox @elseif($transaksi->status == 'Proses') fa-cog @elseif($transaksi->status == 'Selesai') fa-check @else fa-check-circle @endif"></i>
                                                {{ $transaksi->status }}
                                            </span>
                                            <span class="badge @if($transaksi->status_pembayaran == 'Lunas') badge-success @else badge-danger @endif ml-1">
                                                <i class="fas @if($transaksi->status_pembayaran == 'Lunas') fa-check-circle @else fa-exclamation @endif"></i>
                                                {{ $transaksi->status_pembayaran }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-right">
                                    <a href="{{ route('pelanggan.pesanan.show', $transaksi->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                    @if($transaksi->metode_pembayaran == 'qris' && $transaksi->status_pembayaran != 'Lunas')
                                        <span class="badge badge-warning ml-2">
                                            <i class="fas fa-qrcode"></i> Tunggu Bayar
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-2"><strong>Belum ada laundry yang sedang aktif</strong></p>
                            <p class="text-muted small">Buat pesanan baru untuk memulai layanan laundry Anda</p>
                            <a href="{{ route('pelanggan.pesanan.create') }}" class="btn btn-primary btn-sm mt-3">
                                <i class="fas fa-plus"></i> Buat Pesanan Baru
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5><i class="icon fas fa-info-circle"></i> Informasi Penting!</h5>
                <ul class="mb-0 mt-2">
                    <li>✓ Total akhir akan disesuaikan setelah penimbangan di lokasi kami</li>
                    <li>✓ Pakaian yang tidak diambil lebih dari 30 hari akan menjadi milik kami</li>
                    <li>✓ Untuk pembayaran QRIS, klik tombol "Lanjutkan Pembayaran" di halaman detail pesanan</li>
                    <li>✓ Hubungi kami jika ada pertanyaan tentang pesanan Anda</li>
                </ul>
            </div>
        </div>
    </div>

@stop

@section('css')
    <style>
        .small-box {
            border-radius: 0.25rem;
            margin-bottom: 20px;
        }
        .small-box .inner h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            white-space: nowrap;
            padding: 0;
        }
        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .mailbox-read-message {
            background-color: #f8f9fa;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }
        .mailbox-read-message:hover {
            background-color: #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .card-header {
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
        }
    </style>
@stop
