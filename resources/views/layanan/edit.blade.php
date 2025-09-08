@extends('adminlte::page')

@section('title', 'Edit Layanan')

@section('content_header')
    <h1>Edit Data Layanan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.layanan.update', $layanan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_layanan">Nama Layanan</label>
                    <input type="text" name="nama_layanan" class="form-control @error('nama_layanan') is-invalid @enderror" id="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required>
                    @error('nama_layanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="harga_per_kg">Harga per KG</label>
                    <input type="number" name="harga_per_kg" class="form-control @error('harga_per_kg') is-invalid @enderror" id="harga_per_kg" value="{{ old('harga_per_kg', $layanan->harga_per_kg) }}" required>
                    @error('harga_per_kg')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="estimasi_waktu">Estimasi Waktu (Contoh: 2 Hari)</label>
                    <input type="text" name="estimasi_waktu" class="form-control @error('estimasi_waktu') is-invalid @enderror" id="estimasi_waktu" value="{{ old('estimasi_waktu', $layanan->estimasi_waktu) }}" required>
                    @error('estimasi_waktu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
