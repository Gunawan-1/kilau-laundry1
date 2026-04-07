@extends('adminlte::page')

@section('title', 'Buat Pesanan Laundry Baru')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Buat Pesanan Laundry Baru</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- BLOK UNTUK MENAMPILKAN ERROR -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle"></i> Error!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle"></i> Error!</strong> Terdapat kesalahan pada input Anda:
                <ul class="mb-0 mt-2">
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
        <form action="{{ route('pelanggan.pesanan.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Kolom Kiri: Daftar Item -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Pilih Layanan</h3>
                        </div>
                        <div class="card-body">
                            <div id="item-list" class="space-y-3">
                                <!-- Baris item pertama -->
                                <div class="row item-row align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label" for="layanan_0">Layanan</label>
                                        <select name="detail_transaksi[0][layanan_id]" id="layanan_0" class="form-control layanan-select" required>
                                            <option value="">-- Pilih Layanan --</option>
                                            @foreach($layanans as $layanan)
                                                <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga_per_kg }}">{{ $layanan->nama_layanan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="kuantitas_0">Estimasi Berat (Kg)</label>
                                        <input type="number" step="0.1" id="kuantitas_0" name="detail_transaksi[0][kuantitas]" class="form-control kuantitas-input" required min="0.1">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Subtotal</label>
                                        <input type="text" class="form-control bg-light subtotal-text" readonly>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-item" class="btn btn-sm btn-secondary mt-3">
                                <i class="fas fa-plus"></i> Tambah Item
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Ringkasan -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Ringkasan Pesanan</h3>
                        </div>
                        <div class="card-body">
                            <!-- Menambahkan pilihan diskon manual -->
                            <div class="form-group">
                                <label class="form-label" for="manual_diskon_id">Gunakan Kupon</label>
                                <select name="manual_diskon_id" id="manual_diskon_id" class="form-control">
                                    <option value="" data-tipe="tetap" data-nilai="0">-- Tanpa Kupon --</option>
                                    @foreach($diskons as $diskon)
                                        <option value="{{ $diskon->id }}" data-tipe="{{ $diskon->tipe }}" data-nilai="{{ $diskon->nilai }}">
                                            {{ $diskon->nama_diskon }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted">Subtotal</td>
                                            <td class="text-right font-weight-bold"><span id="total-subtotal-text">Rp 0</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Diskon</td>
                                            <td class="text-right text-success font-weight-bold"><span id="total-diskon-text">Rp 0</span></td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="font-weight-bold">Total Estimasi</td>
                                            <td class="text-right"><strong id="total-bayar-text" class="h5">Rp 0</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group mt-4">
                                <label class="form-label">Pilih Metode Pembayaran</label>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="metode_pembayaran" value="tunai" checked>
                                            <span class="custom-control-label">
                                                <i class="fas fa-money-bill-wave text-success"></i> Bayar Tunai
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="metode_pembayaran" value="qris">
                                            <span class="custom-control-label">
                                                <i class="fas fa-qrcode text-primary"></i> QRIS / Online
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="subtotal" id="subtotal-input">
                            <input type="hidden" name="total_diskon" id="total-diskon-input">
                            <input type="hidden" name="total_bayar" id="total-bayar-input">
                            
                            <p class="text-muted small mt-3"><i class="fas fa-info-circle"></i> Total akhir akan disesuaikan setelah penimbangan ulang di lokasi kami.</p>
                            
                            <button type="submit" class="btn btn-primary btn-block mt-4">
                                <i class="fas fa-check-circle"></i> Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

<!-- Template untuk item baru (disembunyikan) -->
<template id="item-template">
    <div class="row item-row align-items-end mb-3">
        <div class="col-md-6">
            <select name="detail_transaksi[__INDEX__][layanan_id]" class="form-control layanan-select" required>
                <option value="">-- Pilih Layanan --</option>
                @foreach($layanans as $layanan)
                    <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga_per_kg }}">{{ $layanan->nama_layanan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.1" name="detail_transaksi[__INDEX__][kuantitas]" class="form-control kuantitas-input" required min="0.1">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control bg-light subtotal-text" readonly>
        </div>
        <div class="col-md-1 text-center">
            <button type="button" class="btn btn-sm btn-danger remove-item" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</template>

@section('js')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let itemIndex = 1;
        const itemList = document.getElementById('item-list');
        const template = document.getElementById('item-template');
        const manualDiskonSelect = document.getElementById('manual_diskon_id');

        document.getElementById('add-item').addEventListener('click', function() {
            const clone = template.content.cloneNode(true);
            const newRow = clone.querySelector('.item-row');
            
            newRow.querySelector('.layanan-select').name = `detail_transaksi[${itemIndex}][layanan_id]`;
            newRow.querySelector('.kuantitas-input').name = `detail_transaksi[${itemIndex}][kuantitas]`;
            
            itemList.appendChild(newRow);
            itemIndex++;
        });

        itemList.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                e.target.closest('.item-row').remove();
                calculateTotal();
            }
        });

        itemList.addEventListener('input', calculateTotal);
        manualDiskonSelect.addEventListener('change', calculateTotal);

        function calculateTotal() {
            let totalSubtotal = 0;
            let itemsForDiscountCheck = [];

            document.querySelectorAll('#item-list .item-row').forEach(function(row) {
                const layananSelect = row.querySelector('.layanan-select');
                const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                const harga = parseFloat(selectedOption.dataset.harga) || 0;
                const kuantitas = parseFloat(row.querySelector('.kuantitas-input').value) || 0;
                const subtotal = harga * kuantitas;

                row.querySelector('.subtotal-text').value = formatRupiah(subtotal);
                totalSubtotal += subtotal;

                if(layananSelect.value && kuantitas > 0) {
                    itemsForDiscountCheck.push({
                        layanan_id: layananSelect.value,
                        kuantitas: kuantitas
                    });
                }
            });

            const selectedManualDiskon = manualDiskonSelect.options[manualDiskonSelect.selectedIndex];
            const tipeDiskonManual = selectedManualDiskon.dataset.tipe;
            const nilaiDiskonManual = parseFloat(selectedManualDiskon.dataset.nilai) || 0;
            let potonganManual = 0;

            if (tipeDiskonManual === 'persen') {
                potonganManual = (nilaiDiskonManual / 100) * totalSubtotal;
            } else {
                potonganManual = nilaiDiskonManual;
            }

            fetch('{{ route("pelanggan.cekDiskon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ items: itemsForDiscountCheck })
            })
            .then(response => response.json())
            .then(data => {
                const potonganOtomatis = data.potongan || 0;
                const totalDiskon = Math.max(potonganManual, potonganOtomatis);
                const totalBayar = totalSubtotal - totalDiskon;

                document.getElementById('total-subtotal-text').textContent = formatRupiah(totalSubtotal);
                document.getElementById('total-diskon-text').textContent = formatRupiah(totalDiskon);
                document.getElementById('total-bayar-text').textContent = formatRupiah(totalBayar);

                document.getElementById('subtotal-input').value = totalSubtotal;
                document.getElementById('total-diskon-input').value = totalDiskon;
                document.getElementById('total-bayar-input').value = totalBayar;
            })
            .catch(error => {
                console.error('Error:', error);
                const totalBayar = totalSubtotal - potonganManual;
                document.getElementById('total-diskon-text').textContent = formatRupiah(potonganManual);
                document.getElementById('total-bayar-text').textContent = formatRupiah(totalBayar);
            });
        }

        function formatRupiah(angka) {
            if (isNaN(angka)) return "Rp 0";
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }
    });
    </script>
@endsection
