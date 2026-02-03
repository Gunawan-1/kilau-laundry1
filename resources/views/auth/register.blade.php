<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Akun CleanWave</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">
        <!-- Logo dan Nama Laundry -->
        <div class="text-center mb-5">
            <svg class="mx-auto h-16 w-16 text-black" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21.2 5.2c-1.3-1.3-3.4-1.3-4.7 0L12 9.7l-4.5-4.5c-1.3-1.3-3.4-1.3-4.7 0S1 8.6 2.3 9.9L7.2 14l-4.9 4.9c-1.3 1.3-1.3 3.4 0 4.7s3.4 1.3 4.7 0L12 18.3l4.5 4.5c1.3 1.3 3.4 1.3 4.7 0s1.3-3.4 0-4.7L16.8 14l4.9-4.9c1.3-1.3 1.3-3.4 0-4.7z"/>
                </svg>

            
            <h1 class="text-lg font-semibold text-gray-800">Daftar Akun</h1>
            <p class="text-sm text-gray-500">Mulai gunakan layanan Laundry</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-3">
            @csrf

            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   placeholder="Nama Lengkap"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

            <input id="alamat" name="alamat" type="text" value="{{ old('alamat') }}" required
                   placeholder="Alamat"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
            @error('alamat')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

            <input id="nomor_telepon" name="nomor_telepon" type="text" value="{{ old('nomor_telepon') }}" required
                   placeholder="Nomor Telepon"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
            @error('nomor_telepon')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                   placeholder="Email"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

            <!-- Password dengan tombol mata -->
            <div class="relative">
                <input id="password" name="password" type="password" required
                       placeholder="Password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                <button type="button" onclick="togglePassword('password')" class="absolute right-2 top-2/4 -translate-y-2/4 text-gray-600 hover:text-black focus:outline-none" aria-label="Toggle Password Visibility">
                    <!-- SVG icon mata -->
                    <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

            <div class="relative">
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       placeholder="Konfirmasi Password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-2 top-2/4 -translate-y-2/4 text-gray-600 hover:text-black focus:outline-none" aria-label="Toggle Password Confirmation Visibility">
                    <!-- SVG icon mata -->
                    <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            @error('password_confirmation')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

            <button type="submit" class="w-full py-2 bg-black text-white rounded-md hover:bg-gray-800 transition-colors">
                Daftar
            </button>

            <div class="text-center mt-2">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    Sudah punya akun?
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const eyeIcon = document.getElementById("eye-" + id);

            if (input.type === "password") {
                input.type = "text";
                if (eyeIcon) {
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 013.082-4.568" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    `;
                }
            } else {
                input.type = "password";
                if (eyeIcon) {
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7" />
                    `;
                }
            }
        }
    </script>
</body>
</html>
