<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pesanan Laundry Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- BLOK UNTUK MENAMPILKAN ERROR -->
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong>Error!</strong> Terdapat kesalahan pada input Anda:
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- END BLOK ERROR -->

            <form action="{{ route('pelanggan.pesanan.store') }}" method="POST">
                @csrf
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <!-- Kolom Kiri: Daftar Item -->
                    <div class="md:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900">Pilih Layanan</h3>
                                <div id="item-list" class="mt-4 space-y-4">
                                    <!-- Baris item pertama -->
                                    <div class="grid grid-cols-12 gap-4 item-row items-center">
                                        <div class="col-span-6">
                                            <label class="block text-sm font-medium text-gray-700">Layanan</label>
                                            <select name="detail_transaksi[0][layanan_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 layanan-select" required>
                                                <option value="">-- Pilih Layanan --</option>
                                                @foreach($layanans as $layanan)
                                                    <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga_per_kg }}">{{ $layanan->nama_layanan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Estimasi Berat (Kg)</label>
                                            <input type="number" step="0.1" name="detail_transaksi[0][kuantitas]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 kuantitas-input" required min="0.1">
                                        </div>
                                        <div class="col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                            <input type="text" class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 subtotal-text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-item" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Tambah Item</button>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Ringkasan -->
                    <div class="md:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900">Ringkasan Pesanan</h3>
                                
                                <!-- [BARU] Menambahkan pilihan diskon manual -->
                                <div class="mt-4">
                                    <label for="manual_diskon_id" class="block text-sm font-medium text-gray-700">Gunakan Kupon</label>
                                    <select name="manual_diskon_id" id="manual_diskon_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="" data-tipe="tetap" data-nilai="0">-- Tanpa Kupon --</option>
                                        @foreach($diskons as $diskon)
                                            <option value="{{ $diskon->id }}" data-tipe="{{ $diskon->tipe }}" data-nilai="{{ $diskon->nilai }}">
                                                {{ $diskon->nama_diskon }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Subtotal</span>
                                        <span id="total-subtotal-text" class="text-sm font-medium text-gray-900">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Diskon</span>
                                        <span id="total-diskon-text" class="text-sm font-medium text-green-600">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between border-t pt-2 mt-2">
                                        <span class="text-base font-medium text-gray-900">Total Estimasi</span>
                                        <strong id="total-bayar-text" class="text-base font-medium text-gray-900">Rp 0</strong>
                                    </div>
                                </div>
                                <input type="hidden" name="subtotal" id="subtotal-input">
                                <input type="hidden" name="total_diskon" id="total-diskon-input">
                                <input type="hidden" name="total_bayar" id="total-bayar-input">
                                <p class="mt-4 text-xs text-gray-500">Total akhir akan disesuaikan setelah penimbangan ulang di lokasi kami.</p>
                                <button type="submit" class="mt-6 w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Template untuk item baru (disembunyikan) -->
    <template id="item-template">
        <div class="grid grid-cols-12 gap-4 item-row items-center">
            <div class="col-span-6">
                <select name="detail_transaksi[__INDEX__][layanan_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 layanan-select" required>
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($layanans as $layanan)
                        <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga_per_kg }}">{{ $layanan->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-3">
                <input type="number" step="0.1" name="detail_transaksi[__INDEX__][kuantitas]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 kuantitas-input" required min="0.1">
            </div>
            <div class="col-span-2">
                <input type="text" class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 subtotal-text" readonly>
            </div>
            <div class="col-span-1 flex justify-end">
                <button type="button" class="text-red-500 hover:text-red-700 remove-item">&times;</button>
            </div>
        </div>
    </template>

    <x-slot name="scripts">
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            let itemIndex = 1;
            const itemList = document.getElementById('item-list');
            const template = document.getElementById('item-template');
            const manualDiskonSelect = document.getElementById('manual_diskon_id'); // [BARU]

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

            // [UBAH] Gabungkan event listener
            itemList.addEventListener('input', calculateTotal);
            manualDiskonSelect.addEventListener('change', calculateTotal); // [BARU]

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

                // [BARU] Hitung potongan diskon manual (kupon)
                const selectedManualDiskon = manualDiskonSelect.options[manualDiskonSelect.selectedIndex];
                const tipeDiskonManual = selectedManualDiskon.dataset.tipe;
                const nilaiDiskonManual = parseFloat(selectedManualDiskon.dataset.nilai) || 0;
                let potonganManual = 0;

                if (tipeDiskonManual === 'persen') {
                    potonganManual = (nilaiDiskonManual / 100) * totalSubtotal;
                } else { // 'tetap'
                    potonganManual = nilaiDiskonManual;
                }

                // Cek diskon otomatis via fetch
                fetch('{{ route("pelanggan.cekDiskon") }}', { // Pastikan route ini ada
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
                    
                    // [UBAH] Pilih diskon terbesar
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
                    // Fallback jika fetch gagal
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
    </x-slot>
</x-app-layout>
