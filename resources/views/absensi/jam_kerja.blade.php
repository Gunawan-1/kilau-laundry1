@extends('adminlte::page')

@section('title', 'Atur Jam Kerja')

@section('content_header')
    <h1><i class="fas fa-clock mr-2"></i>Pengaturan Jam Kerja</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary shadow">
            <div class="card-body">
                <form action="{{ route('admin.absensi.jam-kerja.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control" value="{{ $jamKerja->jam_masuk ?? '08:00' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Pulang</label>
                        <input type="time" name="jam_pulang" class="form-control" value="{{ $jamKerja->jam_pulang ?? '17:00' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Toleransi Terlambat (Menit)</label>
                        <div class="input-group">
                            <input type="number" name="toleransi_terlambat" class="form-control" value="{{ $jamKerja->toleransi_terlambat ?? 0 }}">
                            <div class="input-group-append">
                                <span class="input-group-text">Menit</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop