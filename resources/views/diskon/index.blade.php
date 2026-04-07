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
                        <th width="150px">Status</th>
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
    <label class="custom-switch">
        <input type="checkbox" 
               class="status-toggle"
               data-id="{{ $diskon->id }}"
               {{ $diskon->status ? 'checked' : '' }}>
        <span class="slider round"></span>
    </label>
    <span id="status-text-{{ $diskon->id }}" class="ml-2 small font-weight-bold {{ $diskon->status ? 'text-success' : 'text-danger' }}">
        {{ $diskon->status ? 'Aktif' : 'Nonaktif' }}
    </span>
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
                                <div class="alert alert-danger mb-0">
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

@section('css')
<style>
    /* Desain Tombol Switch (Toggle) */
    .custom-switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 20px;
        vertical-align: middle;
    }

    .custom-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #fd7e14; /* Warna orange toggle */
    }

    input:checked + .slider:before {
        transform: translateX(18px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $('.status-toggle').on('change', function() {
            const diskonId = $(this).data('id');
            const isChecked = $(this).prop('checked');
            const statusText = $('#status-text-' + diskonId);
            
            // Tambahkan efek loading tipis (opsional)
            statusText.css('opacity', '0.5');

            $.ajax({
                url: "{{ url('admin/diskon') }}/" + diskonId + "/status",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PATCH"
                },
                success: function(response) {
                    // Update teks dan warna secara instan tanpa reload
                    if (isChecked) {
                        statusText.text('Aktif').removeClass('text-danger').addClass('text-success');
                    } else {
                        statusText.text('Nonaktif').removeClass('text-success').addClass('text-danger');
                    }
                },
                error: function() {
                    alert('Gagal mengubah status. Silakan coba lagi.');
                    // Kembalikan posisi switch jika gagal
                    $(this).prop('checked', !isChecked);
                },
                complete: function() {
                    statusText.css('opacity', '1');
                }
            });
        });
    });
</script>
@stop
@stop