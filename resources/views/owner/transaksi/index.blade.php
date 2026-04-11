@extends('adminlte::page')

@section('title', 'Semua Transaksi')

@section('content_header')
    <h1>Semua Transaksi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Semua Transaksi</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Invoice</th>
                        <th>Pelanggan</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaksi->kode_invoice }}</td>
                            <td>{{ $transaksi->pelanggan->nama ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $transaksi->status == 'Baru' ? 'badge-info' : ($transaksi->status == 'Proses' ? 'badge-warning' : 'badge-success') }}">
                                    {{ $transaksi->status }}
                                </span>
                            </td>
                            <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('owner.transaksi.show', $transaksi->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-danger">
                                    Data transaksi belum tersedia.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
@stop