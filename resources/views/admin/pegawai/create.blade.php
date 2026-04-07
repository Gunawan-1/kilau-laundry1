@extends('adminlte::page')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Pegawai Baru</h3>
    </div>
    
    <form action="{{ route('admin.pegawai.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
            </div>

            <div class="form-group">
                <label>Email / Username</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="email@contoh.com">
            </div>

            <div class="form-group">
                <label>Nomor HP</label>
                <input type="text" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon') }}" placeholder="0812xxxx">
            </div>

            <div class="form-group">
                <label>Jabatan (Role)</label>
                <select name="role" class="form-control">
                    <option value="admin">Admin</option>
                    <option value="pegawai" selected>Pegawai</option>
                    <option value="owner">Owner</option>
                </select>
            </div>

            <hr>
            <p class="text-muted">* Atur password awal untuk akun ini</p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Pegawai
            </button>
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-default">Kembali</a>
        </div>
    </form>
</div>
@stop