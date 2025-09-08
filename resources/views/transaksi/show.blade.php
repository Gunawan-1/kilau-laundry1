@extends('adminlte::page')

@section('title', 'Invoice ' . $transaksi->kode_invoice)

@section('content_header')
    <h1>Detail Transaksi / Invoice</h1>
@stop

@section('content')
    <div class="invoice p-3 mb-3">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h4>
                    <i class="fas fa-globe"></i> Laundry Anda.
                    <small class="float-right">Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y') }}</small>
                </h4>
            </div>
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                Dari
                <address>
                    <strong>Laundry Anda</strong><br>
                    Jalan Pahlawan No. 123<br>
                    Bandung, Jawa Barat<br>
                    Telepon: (022) 123-4567
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                Untuk
                <address>
                    <strong>{{ $transaksi->pelanggan->nama }}</strong><br>
                    {{ $transaksi->pelanggan->alamat }}<br>
                    Telepon: {{ $transaksi->pelanggan->nomor_telepon }}
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Invoice #{{ $transaksi->kode_invoice }}</b><br>
                <br>
                <b>Tanggal Selesai:</b> {{ $transaksi->tanggal_selesai ? \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') : 'Belum Selesai' }}<br>
                <b>Kasir:</b> {{ $transaksi->user->name }}
            </div>
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Qty (Kg)</th>
                            <th>Layanan</th>
                            <th>Harga per Kg</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detailTransaksis as $detail)
                        <tr>
                            <td>{{ $detail->kuantitas }}</td>
                            <td>{{ $detail->layanan->nama_layanan }}</td>
                            <td>Rp {{ number_format($detail->harga) }}</td>
                            <td>Rp {{ number_format($detail->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-6">
                <p class="lead">Status Pembayaran:</p>
                @if($transaksi->status_pembayaran == 'Lunas')
                    <h2 class="text-success"><strong>LUNAS</strong></h2>
                @else
                    <h2 class="text-danger"><strong>BELUM LUNAS</strong></h2>
                @endif
            </div>
            <div class="col-6">
                <p class="lead">Total Tagihan</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td>Rp {{ number_format($transaksi->subtotal) }}</td>
                        </tr>
                        <tr>
                            <th>Diskon:</th>
                            <td>- Rp {{ number_format($transaksi->diskon) }}</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td><strong>Rp {{ number_format($transaksi->total_bayar) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <a href="#" onclick="window.print();" class="btn btn-default"><i class="fas fa-print"></i> Cetak</a>
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
@stop
