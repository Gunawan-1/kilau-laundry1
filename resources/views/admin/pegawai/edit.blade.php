@extends('adminlte::page')
@section('title', 'Edit Pegawai')
@section('content')
<div class="card card-warning">
    <div class="card-header"><h3 class="card-title">Edit Data Pegawai</h3></div>
    <form action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ $pegawai->name }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nomor HP</label>
                <input type="text" name="nomor_telepon" value="{{ $pegawai->nomor_telepon }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Aktif" {{ $pegawai->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ $pegawai->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Update Data</button>
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-default">Batal</a>
        </div>
    </form>
</div>
@stop