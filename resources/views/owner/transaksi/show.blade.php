@extends('adminlte::page')

@section('title', 'Invoice ' . $transaksi->kode_invoice)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Detail Transaksi</h1>
        <div class="no-print">
            <button onclick="window.print();" class="btn btn-dark">
                <i class="fas fa-print"></i> Cetak Struk
            </button>
            <a href="{{ route('owner.transaksi.index') }}" class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6">
            <div class="receipt p-4 shadow-sm rounded bg-white mx-auto">
                <div class="text-center mb-3">
                    <h2 class="receipt-title mb-1">CleanWave Laundry</h2>
                    <p class="receipt-subtitle mb-1">Jalan Pahlawan No. 123, Bandung</p>
                    <p class="receipt-subtitle">Telp: 0812-3456-7890</p>
                </div>

                <div class="receipt-divider mb-3"></div>

                <div class="receipt-meta mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-uppercase text-muted small">Invoice</span>
                        <strong>#{{ $transaksi->kode_invoice }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-uppercase text-muted small">Tanggal Masuk</span>
                        <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d F Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-uppercase text-muted small">Status Bayar</span>
                        @if($transaksi->status_pembayaran == 'Lunas')
                            <span class="badge badge-success receipt-badge">LUNAS</span>
                        @else
                            <span class="badge badge-danger receipt-badge">BELUM LUNAS</span>
                        @endif
                    </div>
                </div>

                <div class="receipt-divider mb-3"></div>

                <div class="receipt-customer mb-3">
                    <p class="text-uppercase text-muted small mb-1">Pelanggan</p>
                    <p class="mb-0"><strong>{{ $transaksi->pelanggan->nama }}</strong></p>
                    <p class="mb-0">{{ $transaksi->pelanggan->alamat }}</p>
                    <p class="mb-0">{{ $transaksi->pelanggan->nomor_telepon }}</p>
                </div>

                <div class="receipt-divider mb-2"></div>

                <div class="receipt-items mb-3">
                    @foreach($transaksi->detailTransaksis as $detail)
                        <div class="d-flex justify-content-between receipt-item">
                            <div>
                                <div class="receipt-item-name">{{ $detail->layanan->nama_layanan }}</div>
                                <div class="receipt-item-meta">{{ number_format($detail->kuantitas, 2) }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}</div>
                            </div>
                            <strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                        </div>
                    @endforeach
                </div>

                <div class="receipt-divider mb-2"></div>

                <div class="receipt-summary mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Subtotal</span>
                        <strong>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Diskon</span>
                        <strong>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between receipt-total">
                        <span class="font-weight-bold">Total Bayar</span>
                        <strong>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</strong>
                    </div>
                </div>

                @if($transaksi->metode_pembayaran == 'qris' && $transaksi->status_pembayaran != 'Lunas' && $transaksi->snap_token)
                    <div class="alert alert-info mb-3 no-print">
                        <strong>Pembayaran QRIS</strong><br>
                        Klik tombol untuk melanjutkan pembayaran.
                    </div>
                    <button id="pay-button" class="btn btn-primary btn-block no-print mb-3">
                        <i class="fas fa-qrcode"></i> Bayar QRIS
                    </button>
                    <script src="{{ config('services.midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
                    <script>
                        (function() {
                            const payUrl = '{{ route('transaksi.updatePaymentStatus', $transaksi->id) }}';
                            const csrfToken = '{{ csrf_token() }}';
                            const snapToken = '{{ $transaksi->snap_token }}';
                            const payButton = document.getElementById('pay-button');

                            async function markPaymentLunas() {
                                try {
                                    const response = await fetch(payUrl, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({})
                                    });
                                    if (!response.ok) {
                                        console.error('Gagal memperbarui status pembayaran');
                                        return false;
                                    }
                                    const data = await response.json();
                                    return data.success === true;
                                } catch (error) {
                                    console.error(error);
                                    return false;
                                }
                            }

                            function handlePaymentClick() {
                                if (typeof snap === 'undefined' || !snapToken) {
                                    alert('Sistem pembayaran belum siap.');
                                    return;
                                }
                                snap.pay(snapToken, {
                                    onSuccess: async function(result) {
                                        const updated = await markPaymentLunas();
                                        if (updated) {
                                            alert('Pembayaran berhasil!');
                                            setTimeout(() => location.reload(), 1500);
                                        }
                                    },
                                    onPending: async function(result) {
                                        alert('Pembayaran pending.');
                                        await markPaymentLunas();
                                        setTimeout(() => location.reload(), 1500);
                                    },
                                    onError: function() {
                                        alert('Pembayaran gagal.');
                                    }
                                });
                            }

                            if (payButton) {
                                payButton.addEventListener('click', handlePaymentClick);
                            }
                        })();
                    </script>
                @endif

                <div class="text-center mt-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $transaksi->kode_invoice }}" width="90" class="img-fluid mb-2" alt="QR Code">
                    <p class="receipt-note mb-0">Scan untuk cek status</p>
                </div>

                <div class="receipt-footer text-center mt-3">
                    <p class="mb-1 receipt-thanks">*** Terima Kasih Atas Kepercayaan Anda ***</p>
                    <p class="receipt-subtitle mb-0">Pakaian yang tidak diambil lebih dari 30 hari diluar tanggung jawab kami.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .receipt {
        max-width: 420px;
        margin: 0 auto;
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #212529;
    }
    .receipt-title {
        font-size: 24px;
        letter-spacing: 1px;
        margin-bottom: 0;
    }
    .receipt-subtitle {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 0;
    }
    .receipt-divider {
        height: 1px;
        background: #e9ecef;
        margin: 0 auto;
    }
    .receipt-meta .text-muted,
    .receipt-customer .text-muted {
        color: #6c757d;
    }
    .receipt-badge {
        font-size: 11px;
        padding: 5px 10px;
    }
    .receipt-item {
        border-bottom: 1px dashed #dee2e6;
        padding: 10px 0;
    }
    .receipt-item:last-child {
        border-bottom: none;
    }
    .receipt-item-name {
        font-weight: 600;
    }
    .receipt-item-meta {
        font-size: 12px;
        color: #6c757d;
    }
    .receipt-summary .receipt-total {
        font-size: 16px;
        border-top: 1px solid #dee2e6;
        padding-top: 10px;
        margin-top: 10px;
    }
    .receipt-footer {
        font-size: 12px;
        color: #6c757d;
    }
    .receipt-thanks {
        font-size: 13px;
        font-weight: 700;
    }
    @media print {
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
        }
        .main-footer,
        .content-header,
        .no-print,
        .main-sidebar,
        .navbar {
            display: none !important;
        }
        .content-wrapper,
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .receipt {
            max-width: 80mm;
            min-width: 80mm;
            box-shadow: none !important;
            border: none !important;
            padding: 10px !important;
            font-size: 12px !important;
        }
    }
</style>
