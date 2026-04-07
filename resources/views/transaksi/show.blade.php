@extends('adminlte::page')

@section('title', 'Invoice ' . $transaksi->kode_invoice)

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Detail Transaksi</h1>
        <div class="no-print">
            <button onclick="window.print();" class="btn btn-dark">
                <i class="fas fa-print"></i> Cetak Struk
            </button>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="invoice p-5 shadow-sm rounded bg-white">
                
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h2 class="font-weight-bold text-uppercase mb-0" style="letter-spacing: 1px;">CleanWave Laundry</h2>
                        <p class="text-muted mb-0">Jalan Pahlawan No. 123, Bandung</p>
                        <p class="text-muted small">Telp: 0812-3456-7890</p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h4 class="text-muted mb-1">INVOICE</h4>
                        <span class="h5 font-weight-bold text-dark">#{{ $transaksi->kode_invoice }}</span>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="row mb-4 mt-4">
                    <div class="col-sm-6">
                        <p class="text-muted mb-1 text-uppercase small font-weight-bold">Ditagihkan Kepada:</p>
                        <address>
                            <strong class="h5 text-dark">{{ $transaksi->pelanggan->nama }}</strong><br>
                            <span class="text-muted">{{ $transaksi->pelanggan->alamat }}</span><br>
                            <i class="fas fa-phone fa-xs text-muted"></i> {{ $transaksi->pelanggan->nomor_telepon }}
                        </address>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="mb-2">
                            <span class="text-muted small text-uppercase font-weight-bold">Tanggal Masuk:</span><br>
                            <span class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d F Y') }}</span>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase font-weight-bold">Status Pembayaran:</span><br>
                            @if($transaksi->status_pembayaran == 'Lunas')
                                <span class="badge badge-success px-3 py-2">SUDAH LUNAS</span>
                            @else
                                <span class="badge badge-danger px-3 py-2">BELUM LUNAS</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 border-0 text-uppercase small">Layanan</th>
                                    <th class="text-center py-3 border-0 text-uppercase small">Kuantitas</th>
                                    <th class="text-right py-3 border-0 text-uppercase small">Harga</th>
                                    <th class="text-right py-3 border-0 text-uppercase small">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->detailTransaksis as $detail)
                                <tr>
                                    <td class="py-3 font-weight-bold text-dark">{{ $detail->layanan->nama_layanan }}</td>
                                    <td class="text-center py-3">{{ number_format($detail->kuantitas, 2) }}</td>
                                    <td class="text-right py-3">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="text-right py-3 font-weight-bold text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row justify-content-end mt-4">
                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Subtotal Item</td>
                                    <td class="text-right font-weight-bold">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Diskon Promo</td>
                                    <td class="text-right text-success font-weight-bold">- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="h5 font-weight-bold text-dark py-3">TOTAL AKHIR</td>
                                    <td class="h4 font-weight-bold text-right py-3 text-primary">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 text-center border-top">
                    @if($transaksi->metode_pembayaran == 'qris' && $transaksi->status_pembayaran != 'Lunas' && $transaksi->snap_token)
                        <div class="alert alert-info mb-3 no-print">
                            <strong>Pembayaran QRIS</strong> - Pesanan belum dibayar. Klik tombol untuk melanjutkan pembayaran.
                        </div>
                        <button id="pay-button" class="btn btn-primary mb-3 no-print">
                            <i class="fas fa-qrcode"></i> Lanjutkan Pembayaran QRIS
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
                                            const error = await response.text();
                                            console.error('Gagal memperbarui status pembayaran:', error);
                                            return false;
                                        }

                                        const data = await response.json();
                                        return data.success === true;
                                    } catch (error) {
                                        console.error('Error marking payment:', error);
                                        return false;
                                    }
                                }

                                function handlePaymentClick() {
                                    if (typeof snap === 'undefined') {
                                        alert('Sistem pembayaran sedang dimuat. Silakan tunggu sebentar dan coba lagi.');
                                        console.error('snap object not found');
                                        return;
                                    }

                                    if (!snapToken) {
                                        alert('Terjadi kesalahan: snap token tidak ditemukan.');
                                        console.error('snap token is empty');
                                        return;
                                    }

                                    snap.pay(snapToken, {
                                        onSuccess: async function(result) {
                                            console.log('Pembayaran sukses:', result);
                                            const updated = await markPaymentLunas();
                                            if (updated) {
                                                alert('Pembayaran berhasil!');
                                                setTimeout(() => location.reload(), 1500);
                                            } else {
                                                alert('Pembayaran tercatat, namun gagal update status. Silakan refresh.');
                                                setTimeout(() => location.reload(), 2000);
                                            }
                                        },
                                        onPending: async function(result) {
                                            console.log('Pembayaran pending:', result);
                                            alert('Pembayaran dalam proses. Silakan tunggu...');
                                            const updated = await markPaymentLunas();
                                            if (updated) {
                                                setTimeout(() => location.reload(), 1500);
                                            }
                                        },
                                        onError: function(result) {
                                            console.error('Pembayaran gagal:', result);
                                            alert('Pembayaran gagal. Silakan coba lagi.');
                                        },
                                        onClose: function() {
                                            console.log('Popup pembayaran ditutup');
                                        }
                                    });
                                }

                                if (payButton) {
                                    payButton.addEventListener('click', handlePaymentClick);
                                }

                                console.log('Payment token available:', !!snapToken);
                                console.log('Pay button found:', !!payButton);
                            })();
                        </script>
                    @endif
                    <div class="qr-placeholder mb-3 no-print">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $transaksi->kode_invoice }}" width="80" class="img-thumbnail">
                        <p class="small text-muted mt-1">Scan untuk cek status</p>
                    </div>
                    <p class="text-dark font-weight-bold mb-1 small text-uppercase">*** Terima Kasih Atas Kepercayaan Anda ***</p>
                    <p class="text-muted small">Pakaian yang tidak diambil lebih dari 30 hari di luar tanggung jawab kami.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Style Tambahan untuk Tampilan Layar */
    .invoice {
        border-top: 6px solid #343a40 !important;
        position: relative;
    }
    .divider {
        border-top: 1px dashed #dee2e6;
        width: 100%;
    }
    .table thead th {
        letter-spacing: 0.5px;
    }

    /* Pengaturan Cetak (Printer) */
    @media print {
        .main-footer, 
        .content-header, 
        .no-print,
        .main-sidebar,
        .navbar { 
            display: none !important; 
        }
        
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            background-color: white !important;
        }

        .invoice { 
            border: none !important; 
            box-shadow: none !important;
            padding: 0 !important; 
            width: 100%;
            margin: 0 !important;
        }

        body { 
            background-color: #fff !important; 
            font-size: 14px;
        }

        .container-fluid {
            width: 100%;
            padding: 0;
        }

        .col-md-8 {
            max-width: 100%;
            flex: 0 0 100%;
        }
    }
</style>
@stop