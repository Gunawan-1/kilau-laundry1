@extends('adminlte::page')

@section('title', 'Profil Laundry')

@section('content_header')
    <h1 class="font-weight-bold">Profil Laundry</h1>
@stop

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row">
        {{-- Kolom Kiri untuk Informasi Teks --}}
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Detail Informasi Bisnis</h3>
                </div>
                <div class="card-body">
                    {{-- Nama Laundry --}}
                    <div class="form-group">
                        <label for="nama_laundry">Nama Laundry</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-store"></i></span>
                            </div>
                            <input type="text" name="nama_laundry" id="nama_laundry" class="form-control @error('nama_laundry') is-invalid @enderror"
                                   value="{{ old('nama_laundry', $profil->nama_laundry) }}" required>
                            @error('nama_laundry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                         <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $profil->alamat) }}</textarea>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Nomor Telepon --}}
                    <div class="form-group">
                        <label for="nomor_telepon">Nomor Telepon</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror"
                                   value="{{ old('nomor_telepon', $profil->nomor_telepon) }}" required>
                            @error('nomor_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Email (Opsional)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $profil->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Deskripsi Singkat --}}
                    <div class="form-group">
                        <label for="deskripsi_singkat">Deskripsi Singkat (Opsional)</label>
                        <textarea name="deskripsi_singkat" id="deskripsi_singkat" class="form-control @error('deskripsi_singkat') is-invalid @enderror" rows="3" placeholder="Contoh: Laundry Cepat, Bersih, dan Wangi.">{{ old('deskripsi_singkat', $profil->deskripsi_singkat) }}</textarea>
                        @error('deskripsi_singkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan untuk Logo --}}
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Logo Laundry</h3>
                </div>
                <div class="card-body text-center">
                    <div class="form-group">
                        @if ($profil->logo)
                            <img src="{{ Storage::url($profil->logo) }}" alt="Logo Saat Ini" class="img-fluid img-thumbnail mb-3" style="max-height: 200px;">
                        @else
                            <div class="border rounded p-4 mb-3 bg-light">
                                <i class="fas fa-image fa-5x text-muted"></i>
                                <p class="text-muted mt-2">Belum ada logo</p>
                            </div>
                        @endif

                        <div class="custom-file">
                           <input type="file" name="logo" class="custom-file-input @error('logo') is-invalid @enderror" id="logo">
                           <label class="custom-file-label" for="logo">Pilih file baru...</label>
                           @error('logo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <small class="form-text text-muted mt-2">Kosongkan jika tidak ingin mengubah logo. (Max: 2MB)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@push('js')
<script>
    // Script untuk menampilkan nama file di input file bootstrap
    $('.custom-file-input').on('change', function(event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });
</script>
@endpush