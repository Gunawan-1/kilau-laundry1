@extends('adminlte::page')

@section('title', 'Tambah Transaksi Baru')

@section('content_header')
    <h1>Tambah Transaksi Baru</h1>
@stop

@section('content')

<!-- BLOK UNTUK MENAMPILKAN ERROR -->
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Terdapat kesalahan pada input Anda:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<!-- END BLOK ERROR -->

<form action="{{ route('admin.transaksi.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Item Laundry</h3>
                </div>
                <div class="card-body">
                    <div id="item-list">
                        <!-- Baris item pertama -->
                        <div class="row item-row mb-3">
                            <div class="col-md-5">
                                <label>Layanan</label>
                                <select name="detail_transaksi[0][layanan_id]" class="form-control layanan-select" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach($layanans as $layanan)
                                        <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga_per_kg }}">{{ $layanan->nama_layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Berat (Kg)</label>
                                <input type="number" step="0.1" name="detail_transaksi[0][kuantitas]" class="form-control kuantitas-input" required min="0.1">
                            </div>
                            <div class="col-md-2">
                                <label>Subtotal</label>
                                <input type="text" class="form-control subtotal-text" readonly>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <!-- Tombol hapus tidak ada di baris pertama -->
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-item" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Item</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Transaksi</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="pelanggan_id">Pelanggan</label>
                        <select name="pelanggan_id" id="pelanggan_id" class="form-control select2" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }} - {{ $pelanggan->nomor_telepon }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- [BARU] Menambahkan pilihan diskon manual -->
                    <div class="form-group">
                        <label for="manual_diskon_id">Pilih Diskon (Manual)</label>
                        <select name="manual_diskon_id" id="manual_diskon_id" class="form-control">
                            <option value="" data-tipe="tetap" data-nilai="0">-- Tanpa Diskon Manual --</option>
                            @foreach($diskons as $diskon)
                                <option value="{{ $diskon->id }}" data-tipe="{{ $diskon->tipe }}" data-nilai="{{ $diskon->nilai }}">
                                    {{ $diskon->nama_diskon }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <hr>

                    <h5>Ringkasan Biaya</h5>
                    <table class="table">
                        <tr>
                            <th>Subtotal</th>
                            <td><span id="total-subtotal-text">Rp 0</span></td>
                        </tr>
                        <tr>
                            <th>Diskon</th>
                            <td><span id="total-diskon-text" class="text-success">Rp 0</span></td>
                        </tr>
                        <tr>
                            <th>Total Bayar</th>
                            <td><strong id="total-bayar-text">Rp 0</strong></td>
                        </tr>
                    </table>
                    
                    {{-- Input tersembunyi ini tidak wajib karena backend menghitung ulang,
                         tapi tetap berguna untuk mengirim data awal --}}
                    <input type="hidden" name="subtotal" id="subtotal-input">
                    <input type="hidden" name="total_diskon" id="total-diskon-input">
                    <input type="hidden" name="total_bayar" id="total-bayar-input">
                    <hr>
                    <div class="form-group">
                        <label for="status">Status Laundry</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Baru">Baru</option>
                            <option value="Proses">Proses</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_pembayaran">Status Pembayaran</label>
                        <select name="status_pembayaran" id="status_pembayaran" class="form-control" required>
                            <option value="Belum Lunas">Belum Lunas</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- TEMPLATE UNTUK ITEM BARU (DISEMBUNYIKAN) -->
<div id="item-template" style="display: none;">
    <div class="row item-row mb-3">
        <div class="col-md-5">
            <select name="detail_transaksi[__INDEX__][layanan_id]" class="form-control layanan-select" required>
                <option value="">-- Pilih Layanan --</option>
                @foreach($layanans as $layanan)
                    <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga_per_kg }}">{{ $layanan->nama_layanan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" step="0.1" name="detail_transaksi[__INDEX__][kuantitas]" class="form-control kuantitas-input" required min="0.1">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control subtotal-text" readonly>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2').select2();

        let itemIndex = 1;

        // Fungsi untuk menambah baris item baru
        $('#add-item').click(function() {
            let newRowHtml = $('#item-template').html().replace(/__INDEX__/g, itemIndex);
            $('#item-list').append(newRowHtml);
            itemIndex++;
        });

        // Fungsi untuk menghapus baris item
        $('#item-list').on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            calculateTotal();
        });

        // Event listener untuk memicu kalkulasi
        $('#item-list').on('change keyup', '.layanan-select, .kuantitas-input', calculateTotal);
        // [BARU] Event listener untuk diskon manual
        $('#manual_diskon_id').on('change', calculateTotal);

        // --- [FUNGSI KALKULASI YANG DIPERBARUI] ---
        function calculateTotal() {
            let totalSubtotal = 0;
            let itemsForDiscountCheck = [];

            // Hitung subtotal dari semua item
            $('.item-row').each(function() {
                const row = $(this);
                const layananSelect = row.find('.layanan-select');
                const harga = parseFloat(layananSelect.find('option:selected').data('harga')) || 0;
                const kuantitas = parseFloat(row.find('.kuantitas-input').val()) || 0;
                const subtotal = harga * kuantitas;

                row.find('.subtotal-text').val(formatRupiah(subtotal));
                totalSubtotal += subtotal;

                if (layananSelect.val() && kuantitas > 0) {
                    itemsForDiscountCheck.push({
                        layanan_id: layananSelect.val(),
                        kuantitas: kuantitas
                    });
                }
            });

            // [BARU] Hitung potongan diskon manual
            const selectedManualDiskon = $('#manual_diskon_id option:selected');
            const tipeDiskonManual = selectedManualDiskon.data('tipe');
            const nilaiDiskonManual = parseFloat(selectedManualDiskon.data('nilai')) || 0;
            let potonganManual = 0;

            if (tipeDiskonManual === 'persen') {
                potonganManual = (nilaiDiskonManual / 100) * totalSubtotal;
            } else { // 'tetap'
                potonganManual = nilaiDiskonManual;
            }

            // Panggil AJAX untuk mendapatkan diskon otomatis
            $.ajax({
                url: '{{ route("admin.transaksi.cekDiskon") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    items: itemsForDiscountCheck
                },
                success: function(response) {
                    const potonganOtomatis = response.potongan || 0;
                    
                    // [UBAH] Pilih diskon terbesar antara manual dan otomatis
                    const totalDiskon = Math.max(potonganManual, potonganOtomatis);
                    
                    const totalBayar = totalSubtotal - totalDiskon;

                    // Update tampilan
                    $('#total-subtotal-text').text(formatRupiah(totalSubtotal));
                    $('#total-diskon-text').text(formatRupiah(totalDiskon));
                    $('#total-bayar-text').text(formatRupiah(totalBayar));

                    // Update input hidden
                    $('#subtotal-input').val(totalSubtotal);
                    $('#total-diskon-input').val(totalDiskon);
                    $('#total-bayar-input').val(totalBayar);
                },
                error: function() {
                    console.error('Gagal mengecek diskon otomatis.');
                    // Jika gagal, setidaknya gunakan diskon manual
                    const totalBayar = totalSubtotal - potonganManual;
                    $('#total-subtotal-text').text(formatRupiah(totalSubtotal));
                    $('#total-diskon-text').text(formatRupiah(potonganManual));
                    $('#total-bayar-text').text(formatRupiah(totalBayar));
                    $('#subtotal-input').val(totalSubtotal);
                    $('#total-diskon-input').val(potonganManual);
                    $('#total-bayar-input').val(totalBayar);
                }
            });
        }

        // Helper function untuk format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }
    });
</script>
@endpush