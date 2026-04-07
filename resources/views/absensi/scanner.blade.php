@extends('adminlte::page')

@section('title', 'Scanner Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header text-center">
                <h3 class="card-title">Scan QR Code Pegawai</h3>
            </div>
            <div class="card-body">
                <div id="reader" style="width: 100%"></div>
                
                <div id="result" class="mt-3 text-center">
                    <p class="text-muted">Arahkan QR Code ke kamera</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="absensiModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Absensi Berhasil</h5>
            </div>
            <div class="modal-body text-center">
                <h4 id="namaPegawai"></h4>
                <p id="waktuAbsen"></p>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan scanner sementara agar tidak dobel scan
        html5QrcodeScanner.clear();

        // Kirim data ke server via AJAX
        $.ajax({
            url: "{{ $prosesScanUrl }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                qr_code: decodedText
            },
            success: function(response) {
                if(response.success) {
                    $('#namaPegawai').text(response.nama);
                    $('#waktuAbsen').text(response.waktu);
                    $('#absensiModal').modal('show');
                    
                    // Refresh scanner setelah 3 detik
                    setTimeout(() => { location.reload(); }, 3000);
                } else {
                    alert(response.message);
                    location.reload();
                }
            }
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@stop