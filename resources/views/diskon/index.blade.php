@extends('adminlte::page')

@section('title', 'Manajemen Diskon')

@section('content_header')
    <h1>Manajemen Diskon</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Diskon</h3>
            <div class="card-tools">
                <a href="{{ route('admin.diskon.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Diskon
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
                        <th>No</th>
                        <th>Nama Diskon</th>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Aturan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($diskons as $diskon)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $diskon->nama_diskon }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($diskon->tipe) }}</span></td>
                            <td>{{ $diskon->tipe == 'persen' ? $diskon->nilai . '%' : 'Rp ' . number_format($diskon->nilai) }}</td>
                            <td>
                                @if($diskon->jenis_aturan == 'berdasarkan_layanan_berat' && $diskon->layanan)
                                    Layanan: <strong>{{ $diskon->layanan->nama_layanan }}</strong><br>
                                    Min. Berat: <strong>{{ $diskon->minimal_berat_aturan }} Kg</strong>
                                @else
                                    <span class="badge badge-secondary">Manual</span>
                                @endif
                            </td>
                            <td>
                                @if($diskon->status)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('admin.diskon.destroy', $diskon->id) }}" method="POST">
                                    <a href="{{ route('admin.diskon.edit', $diskon->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-danger">
                                    Data diskon belum tersedia.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $diskons->links() }}
            </div>
        </div>
    </div>
@stop
