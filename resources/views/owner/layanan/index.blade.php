@extends('adminlte::page')

@section('title', 'Data Layanan')

@section('content_header')
    <h1>Data Layanan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Layanan</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Harga per KG</th>
                        <th>Estimasi Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($layanans as $layanan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $layanan->nama_layanan }}</td>
                            <td>Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}</td>
                            <td>{{ $layanan->estimasi_waktu }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="alert alert-danger">
                                    Data layanan belum tersedia.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $layanans->links() }}
            </div>
        </div>
    </div>
@stop