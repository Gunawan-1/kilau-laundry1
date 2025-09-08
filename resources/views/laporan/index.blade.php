@extends('adminlte::page')

@section('title', 'Laporan Transaksi')

@section('content_header')
    <h1>Laporan Transaksi</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filter Laporan</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="form-inline">
            <div class="form-group mb-2">
                <label for="tanggal_mulai" class="mr-2">Dari Tanggal:</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="tanggal_selesai" class="mr-2">Sampai Tanggal:</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
            </div>
            <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ringkasan Laporan ({{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }})</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                        <p>Total Pendapatan (Lunas)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</h3>
                        <p>Total Subtotal</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalDiskon, 0, ',', '.') }}</h3>
                        <p>Total Diskon</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Transaksi</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="laporan-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Tgl Masuk</th>
                        <th>Tgl Selesai</th>
                        <th>Subtotal</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporans as $key => $laporan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><a href="{{ route('admin.transaksi.show', $laporan->id) }}">{{ $laporan->kode_invoice }}</a></td>
                            <td>{{ $laporan->pelanggan->nama ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($laporan->tanggal_masuk)->format('d M Y') }}</td>
                            <td>{{ $laporan->tanggal_selesai ? \Carbon\Carbon::parse($laporan->tanggal_selesai)->format('d M Y') : '-' }}</td>
                            <td>Rp {{ number_format($laporan->subtotal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($laporan->diskon, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($laporan->total_bayar, 0, ',', '.') }}</td>
                            <td>{{ $laporan->status }}</td>
                            <td>{{ $laporan->status_pembayaran }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data untuk rentang tanggal yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    // Inisialisasi DataTables untuk fungsionalitas ekspor dan pencarian
    $(document).ready(function() {
        $('#laporan-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#laporan-table_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush

@push('css')
{{-- Tambahan CSS jika diperlukan untuk styling DataTables --}}
@endpush
