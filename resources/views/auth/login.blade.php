<x-guest-layout title="Login">
    <!-- Login Card -->
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
            
            <!-- Logo & Header -->
            <div class="text-center mb-6">
                <!-- Ganti warna logo ke hitam -->
                <svg class="mx-auto h-16 w-16 text-black" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21.2 5.2c-1.3-1.3-3.4-1.3-4.7 0L12 9.7l-4.5-4.5c-1.3-1.3-3.4-1.3-4.7 0S1 8.6 2.3 9.9L7.2 14l-4.9 4.9c-1.3 1.3-1.3 3.4 0 4.7s3.4 1.3 4.7 0L12 18.3l4.5 4.5c1.3 1.3 3.4 1.3 4.7 0s1.3-3.4 0-4.7L16.8 14l4.9-4.9c1.3-1.3 1.3-3.4 0-4.7z"/>
                </svg>
                <h2 class="mt-4 text-2xl font-bold text-gray-800">Masuk ke Akun Anda</h2>
                <p class="mt-1 text-sm text-gray-500">Selamat datang kembali!</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" required autofocus autocomplete="username"
                        :value="old('email')" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <div class="relative mt-1">
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="block w-full border-gray-300 focus:border-black focus:ring-black rounded-md shadow-sm pr-10">
                        <div onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7" />
                            </svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
<div class="flex items-center justify-between">
    <label for="remember_me" class="inline-flex items-center">
        <input id="remember_me" type="checkbox" name="remember"
            class="rounded border-gray-300 text-black shadow-sm focus:ring-black">
        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
    </label>

    @if (Route::has('password.request'))
        <a class="text-sm text-indigo-600 hover:underline"
            href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
        </a>
    @endif
</div>

                <!-- Login Button -->
                <div>
                    <x-primary-button class="w-full justify-center py-2 text-white bg-black hover:bg-gray-800 text-sm font-semibold">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

                
<!-- Register -->
<div class="text-center text-sm text-gray-500 mt-4">
    Belum punya akun?
    <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-semibold">Daftar sekarang</a>
</div>
            </form>
        </div>
    </div>

    <!-- Script toggle eye -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 013.082-4.568M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3l18 18" />
                `;
            } else {
                passwordInput.type = "password";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7" />
                `;
            }
        }
    </script>
</x-guest-layout>
