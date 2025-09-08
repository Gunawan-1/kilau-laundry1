<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan: ') }} {{ $transaksi->kode_invoice }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <!-- Header Invoice -->
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">CleanWave Laundry</h3>
                            <p class="text-sm text-gray-500">Jalan Pahlawan No. 123, Bandung</p>
                        </div>
                        <div class="text-right">
                            <h2 class="text-2xl font-bold text-gray-800">INVOICE</h2>
                            <p class="text-sm text-gray-500">{{ $transaksi->kode_invoice }}</p>
                        </div>
                    </div>

                    <!-- Info Pelanggan & Tanggal -->
                    <div class="mt-8 grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Ditagihkan Kepada:</p>
                            <p class="font-semibold text-gray-800">{{ $transaksi->pelanggan->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $transaksi->pelanggan->alamat }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-600">Tanggal Masuk:</p>
                            <p class="text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d F Y') }}</p>
                            <p class="text-sm font-medium text-gray-600 mt-2">Status Pembayaran:</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaksi->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $transaksi->status_pembayaran }}
                            </span>
                        </div>
                    </div>

                    <!-- Tabel Item -->
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Layanan</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Berat (Kg)</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Harga</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0 text-right text-sm font-semibold text-gray-900">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($transaksi->detailTransaksis as $detail)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ $detail->layanan->nama_layanan }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $detail->kuantitas }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Rp {{ number_format($detail->harga) }}</td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">Rp {{ number_format($detail->subtotal) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="mt-8 flex justify-end">
                        <div class="w-full max-w-xs">
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($transaksi->subtotal) }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Diskon</dt>
                                    <dd class="text-sm font-medium text-green-600">- Rp {{ number_format($transaksi->diskon) }}</dd>
                                </div>
                                <div class="flex justify-between border-t pt-2 mt-2">
                                    <dt class="text-base font-semibold text-gray-900">Total</dt>
                                    <dd class="text-base font-semibold text-gray-900">Rp {{ number_format($transaksi->total_bayar) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-8 border-t pt-4">
                        <a href="{{ route('pelanggan.dashboard') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            &larr; Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
