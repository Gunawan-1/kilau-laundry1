@extends('adminlte::page')

@section('title', 'Data Absensi')

@section('content_header')
    <h1><i class="fas fa-list mr-2"></i>Rekap Absensi Hari Ini</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-info">
        <h3 class="card-title text-white">{{ Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensis as $absen)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $absen->user->name }}</td>
                    <td><span class="badge badge-success">{{ $absen->jam_masuk }}</span></td>
                    <td>
                        @if($absen->jam_keluar)
                            <span class="badge badge-danger">{{ $absen->jam_keluar }}</span>
                        @else
                            <span class="text-muted italic">Belum Pulang</span>
                        @endif
                    </td>
                    <td>
                        @if($absen->status == 'Terlambat')
                            <span class="text-danger font-weight-bold">TERLAMBAT</span>
                        @else
                            <span class="text-success font-weight-bold">TEPAT WAKTU</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-muted">Belum ada aktivitas absensi hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop