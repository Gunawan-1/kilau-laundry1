@extends('adminlte::page')

@section('title', 'Data Pegawai')

@section('content_header')
    <h1><i class="fas fa-user-tie mr-2"></i>Data Pegawai</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Daftar Akun Pegawai & Staff</h3>
    </div>

    <div class="card-body">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawais as $pegawai)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-left font-weight-bold">{{ $pegawai->name }}</td>
                            <td class="text-left">{{ $pegawai->email }}</td>
                            <td>
                                <span class="badge {{ $pegawai->role == 'admin' ? 'badge-danger' : 'badge-info' }}">
                                    {{ ucfirst($pegawai->role) }}
                                </span>
                            </td>
                            <td>{{ $pegawai->nomor_telepon ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $pegawai->status == 'Aktif' ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $pegawai->status ?? 'Aktif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">Belum ada data pegawai yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop