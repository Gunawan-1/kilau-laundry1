@extends('adminlte::page')

@section('title', 'Data Pegawai')

@section('content_header')
    <h1><i class="fas fa-user-tie mr-2"></i>Data Pegawai</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title text-white">Daftar Akun Pegawai & Staff</h3>
            <a href="{{ route('owner.pegawai.create') }}" class="btn btn-light btn-sm font-weight-bold">
                <i class="fas fa-plus-circle text-primary"></i> Tambah Pegawai
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th width="50px">No</th>
                        <th>Nama Lengkap</th>
                        <th>Email / Username</th>
                        <th>Jabatan</th>
                        <th>No. Telp</th>
                        <th>Status</th>
                        <th width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawais as $pegawai)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-left font-weight-bold">{{ $pegawai->name }}</td>
                            <td class="text-left">{{ $pegawai->email }}</td>
                            <td>
                                <span class="badge {{ $pegawai->role == 'admin' ? 'badge-danger' : ($pegawai->role == 'owner' ? 'badge-warning' : 'badge-info') }}">
                                    {{ ucfirst($pegawai->role) }}
                                </span>
                            </td>
                            <td>{{ $pegawai->nomor_telepon ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $pegawai->status == 'Aktif' ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $pegawai->status ?? 'Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalQR{{ $pegawai->id }}" title="Cetak ID Card">
                                        <i class="fas fa-qrcode"></i>
                                    </button>

                                    <a href="{{ route('owner.pegawai.edit', $pegawai->id) }}" class="btn btn-warning btn-sm" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('owner.pegawai.destroy', $pegawai->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Apakah Anda yakin ingin menghapus pegawai ini?')" class="btn btn-danger btn-sm" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalQR{{ $pegawai->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title text-white">Preview ID Card</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                    </div>
                                    <div class="modal-body text-center" id="printArea{{ $pegawai->id }}">
                                        <div style="border: 2px solid #007bff; padding: 15px; border-radius: 10px; background: #fff; position: relative; overflow: hidden;">
                                            <div style="background: #007bff; color: white; padding: 5px; margin: -15px -15px 15px -15px;">
                                                <small class="font-weight-bold">KILAU LAUNDRY</small>
                                            </div>
                                            
                                            <div class="mb-2">
                                                {!! QrCode::size(150)->margin(1)->generate($pegawai->email) !!}
                                            </div>
                                            
                                            <h5 class="mb-0 font-weight-bold">{{ $pegawai->name }}</h5>
                                            <span class="badge badge-pill badge-primary">{{ strtoupper($pegawai->role) }}</span>
                                            <hr class="my-2">
                                            <small class="text-muted italic">Scan untuk Absensi Pegawai</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary btn-block" onclick="printIDCard('printArea{{ $pegawai->id }}')">
                                            <i class="fas fa-print mr-2"></i>Cetak Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-muted py-4">Belum ada data pegawai yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    function printIDCard(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        
        // Reload agar event javascript/modal tidak mati setelah print
        window.location.reload();
    }
</script>
@stop