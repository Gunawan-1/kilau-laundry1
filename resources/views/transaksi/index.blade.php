@extends('adminlte::page')

@section('title', 'Daftar Transaksi')

@section('content_header')
    <h1>Daftar Transaksi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Transaksi</h3>
            <div class="card-tools">
                <a href="{{ route('admin.transaksi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Transaksi
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Tgl Masuk</th>
                        <th>Total Bayar</th>
                        <th>Status Laundry</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td><strong>{{ $transaksi->kode_invoice }}</strong></td>
                            <td>{{ $transaksi->pelanggan->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d M Y, H:i') }}</td>
                            <td>Rp {{ number_format($transaksi->total_bayar) }}</td>
                            <td>
                                <span class="badge {{ $transaksi->status == 'Selesai' ? 'badge-success' : ($transaksi->status == 'Diambil' ? 'badge-primary' : 'badge-warning') }}">
                                    {{ $transaksi->status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $transaksi->status_pembayaran == 'Lunas' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $transaksi->status_pembayaran }}
                                </span>
                            </td>
                            <td>
                                <form onsubmit="return confirm('Apakah Anda Yakin ingin menghapus data ini?');" action="{{ route('admin.transaksi.destroy', $transaksi->id) }}" method="POST">
                                    <a href="{{ route('admin.transaksi.show', $transaksi->id) }}" class="btn btn-sm btn-info" title="Lihat Invoice">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#updateStatusModal-{{ $transaksi->id }}" title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Modal Update Status -->
                        <div class="modal fade" id="updateStatusModal-{{ $transaksi->id }}" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateStatusModalLabel">Update Status: {{ $transaksi->kode_invoice }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.transaksi.updateStatus', $transaksi->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="status">Status Laundry</label>
                                                <select name="status" class="form-control">
                                                    <option value="Baru" {{ $transaksi->status == 'Baru' ? 'selected' : '' }}>Baru</option>
                                                    <option value="Proses" {{ $transaksi->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                                    <option value="Selesai" {{ $transaksi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="Diambil" {{ $transaksi->status == 'Diambil' ? 'selected' : '' }}>Diambil</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="status_pembayaran">Status Pembayaran</label>
                                                <select name="status_pembayaran" class="form-control">
                                                    <option value="Belum Lunas" {{ $transaksi->status_pembayaran == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                                    <option value="Lunas" {{ $transaksi->status_pembayaran == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
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
