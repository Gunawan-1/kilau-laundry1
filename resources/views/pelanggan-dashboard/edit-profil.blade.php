<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>
<div class="max-w-3xl mx-auto sm:px-6 lg:px-8 py-10">
    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
        <h2 class="text-2xl font-semibold mb-6">Edit Profil</h2>

        @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('pelanggan.profil.update') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-gray-700">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <label class="block text-gray-700">Alamat</label>
                <textarea name="alamat" class="w-full border-gray-300 rounded-md shadow-sm mt-1">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                @error('alamat')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-4">
                <label class="block text-gray-700">Nomor Telepon</label>
                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $pelanggan->nomor_telepon) }}" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                @error('nomor_telepon')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-gray-700">Password Baru (opsional)</label>
                <input type="password" name="password" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
                @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="mb-4">
                <label class="block text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-md shadow-sm mt-1">
            </div>

            <div class="flex justify-end">
                 <a href="{{ route('pelanggan.dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">
        Batal
    </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
