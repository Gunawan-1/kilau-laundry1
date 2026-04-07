@extends('adminlte::page')

@section('title', 'Detail Pesanan')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detail Pesanan: {{ $transaksi->kode_invoice }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Invoice Pesanan</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-1">CleanWave Laundry</h5>
                        <p class="text-muted mb-0">Jalan Pahlawan No. 123, Bandung</p>
                    </div>
                    <div class="col-md-6 text-md-right mt-3 mt-md-0">
                        <h5 class="mb-1">INVOICE</h5>
                        <span class="text-muted">{{ $transaksi->kode_invoice }}</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="text-sm text-muted mb-1">Ditagihkan Kepada:</p>
                        <h5 class="mb-1">{{ $transaksi->pelanggan->nama }}</h5>
                        <p class="text-muted mb-0">{{ $transaksi->pelanggan->alamat }}</p>
                    </div>
                    <div class="col-md-6 text-md-right mt-3 mt-md-0">
                        <p class="text-sm text-muted mb-1">Tanggal Masuk:</p>
                        <h5 class="mb-1">{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d F Y') }}</h5>
                        <p class="text-sm text-muted mb-1 mt-3">Status Pembayaran:</p>
                        <span class="badge badge-{{ $transaksi->status_pembayaran == 'Lunas' ? 'success' : 'danger' }}">
                            {{ $transaksi->status_pembayaran }}
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Layanan</th>
                                <th>Berat (Kg)</th>
                                <th>Harga</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->detailTransaksis as $detail)
                                <tr>
                                    <td>{{ $detail->layanan->nama_layanan }}</td>
                                    <td>{{ $detail->kuantitas }}</td>
                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end mt-4">
                    <div class="col-md-5">
                        <div class="card card-secondary">
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-7 text-muted">Subtotal</dt>
                                    <dd class="col-5 text-right font-weight-bold">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</dd>

                                    <dt class="col-7 text-muted">Diskon</dt>
                                    <dd class="col-5 text-right text-success">- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</dd>

                                    <dt class="col-7 text-dark mt-3">Total</dt>
                                    <dd class="col-5 text-right h5 font-weight-bold mt-3">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                @if($transaksi->metode_pembayaran == 'qris' && $transaksi->status_pembayaran != 'Lunas' && $transaksi->snap_token)
                    <div class="alert alert-info mt-4" role="alert">
                        Pesanan belum dibayar. Klik tombol di bawah untuk melakukan pembayaran.
                    </div>
                    <button id="pay-button" class="btn btn-primary mb-4">
                        <i class="fas fa-qrcode mr-2"></i> Lanjutkan Pembayaran QRIS
                    </button>
                @endif

                <div class="mt-4">
                    <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if($transaksi->metode_pembayaran == 'qris' && $transaksi->status_pembayaran != 'Lunas' && $transaksi->snap_token)
        <script src="{{ config('services.midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
        <script>
            (function() {
                const payUrl = '{{ route('pelanggan.pesanan.updatePaymentStatus', $transaksi->id) }}';
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
                                alert('Pembayaran berhasil! Halaman akan di-refresh.');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                alert('Pembayaran tercatat di Midtrans, namun gagal memperbarui status. Silakan refresh halaman.');
                                setTimeout(() => location.reload(), 2000);
                            }
                        },
                        onPending: async function(result) {
                            console.log('Pembayaran pending:', result);
                            alert('Pembayaran Anda dalam proses. Silakan tunggu...');
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
@endsection
