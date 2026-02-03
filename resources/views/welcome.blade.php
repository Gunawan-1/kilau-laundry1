<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profil->nama_laundry ?? 'CleanFast Laundry' }} - Solusi Laundry Modern & Cepat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .brand-gradient-text {
            background: linear-gradient(to right, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Efek shadow untuk teks di hero section */
        .text-shadow-custom {
            text-shadow: 0px 2px 4px rgba(0,0,0,0.5);
        }
        /* Style untuk kursor Typed.js */
        .typed-cursor {
            font-size: 3.5rem;
            color: #60a5fa;
        }
    </style>
</head>
<body class="bg-slate-50 text-gray-800">

    <!-- Header / Navbar -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 transition-all duration-300">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="flex items-center space-x-4">
    @if($profil && $profil->logo)
        <img src="{{ Storage::url($profil->logo) }}" alt="Logo {{ $profil->nama_laundry }}" class="h-16 w-auto">
        {{-- Pindahkan kode ini ke dalam kondisi IF --}}
        <span class="text-3xl font-extrabold text-blue-600">
            {{ $profil->nama_laundry ?? 'CleanFast' }}<span class="text-slate-800">.</span>
        </span>
    @else
        {{-- Jika tidak ada logo, tetap tampilkan nama laundry --}}
        <span class="text-3xl font-extrabold text-blue-600">
            {{ $profil->nama_laundry ?? 'CleanFast' }}<span class="text-slate-800">.</span>
        </span>
    @endif
</a>
            <div class="hidden md:flex space-x-8 items-center font-medium">
                <a href="#layanan" class="text-slate-600 hover:text-blue-600 transition-colors">Layanan</a>
                <a href="#cara-pesan" class="text-slate-600 hover:text-blue-600 transition-colors">Cara Pesan</a>
                <a href="#faq" class="text-slate-600 hover:text-blue-600 transition-colors">FAQ</a>
                <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 transition-colors">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Daftar</a>
            </div>
            <button id="mobile-menu-button" class="md:hidden text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </nav>
        <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 space-y-2">
            <a href="#layanan" class="block text-slate-600 hover:text-blue-600 py-2">Layanan</a>
            <a href="#cara-pesan" class="block text-slate-600 hover:text-blue-600 py-2">Cara Pesan</a>
            <a href="#faq" class="block text-slate-600 hover:text-blue-600 py-2">FAQ</a>
            <a href="{{ route('login') }}" class="block text-slate-600 hover:text-blue-600 py-2">Login</a>
            <a href="{{ route('register') }}" class="block bg-blue-600 text-white text-center mt-2 px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">Daftar</a>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="hero" class="relative h-screen min-h-[700px] bg-fixed bg-cover bg-center text-white flex items-center">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
            <div class="container mx-auto px-6 text-center relative z-10">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4 text-shadow-custom">
                    Solusi Laundry Modern Untuk <br class="hidden md:block"> <span id="typed-text" class="brand-gradient-text"></span>
                </h1>
                <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto text-slate-200">{{ $profil->deskripsi_singkat ?? 'Pakaian bersih, wangi, dan rapi tanpa repot. Biarkan kami yang urus cucian Anda!' }}</p>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white font-bold px-8 py-4 rounded-lg hover:bg-blue-700 transition-all duration-300 text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 inline-flex items-center space-x-2">
                    <span>Pesan Sekarang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
            {{-- [BARU] Shape Divider --}}
            <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none" style="height: 80px;">
                <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="#FFFFFF"></path></svg>
            </div>
        </section>

        <!-- [BARU] Stats Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="reveal">
                        <h3 class="text-4xl md:text-5xl font-extrabold text-blue-600 counter" data-target="1500">0</h3>
                        <p class="text-slate-500 font-medium mt-2">Pelanggan Puas</p>
                    </div>
                    <div class="reveal">
                        <h3 class="text-4xl md:text-5xl font-extrabold text-blue-600 counter" data-target="25000">0</h3>
                        <p class="text-slate-500 font-medium mt-2">Kg Pakaian Dicuci</p>
                    </div>
                    <div class="reveal">
                        <h3 class="text-4xl md:text-5xl font-extrabold text-blue-600 counter" data-target="5">0</h3>
                        <p class="text-slate-500 font-medium mt-2">Tahun Pengalaman</p>
                    </div>
                    <div class="reveal">
                        <h3 class="text-4xl md:text-5xl font-extrabold text-blue-600 counter" data-target="99">0</h3>
                        <p class="text-slate-500 font-medium mt-2">% Kebersihan Terjamin</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kenapa Memilih Kami? Section -->
        <section id="keunggulan" class="py-20">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 reveal">Kenapa Memilih <span class="text-blue-600">{{ $profil->nama_laundry ?? 'Kami' }}</span>?</h2>
                <p class="text-slate-600 mb-16 max-w-2xl mx-auto reveal">Kami berkomitmen memberikan layanan terbaik dengan teknologi modern untuk hasil yang maksimal.</p>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                     <!-- Feature cards dengan style baru -->
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-100 reveal transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                        <div class="bg-blue-100 text-blue-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md"><svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        <h3 class="text-xl font-bold mb-2">Cepat & Tepat Waktu</h3>
                        <p class="text-slate-600">Proses pengerjaan cepat dan pengantaran sesuai jadwal yang Anda tentukan.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-100 reveal transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                         <div class="bg-green-100 text-green-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md"><svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        <h3 class="text-xl font-bold mb-2">Hasil Higienis</h3>
                        <p class="text-slate-600">Menggunakan deterjen berkualitas dan proses yang higienis untuk pakaian bersih maksimal.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-100 reveal transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                         <div class="bg-yellow-100 text-yellow-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md"><svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg></div>
                        <h3 class="text-xl font-bold mb-2">Harga Terjangkau</h3>
                        <p class="text-slate-600">Kualitas premium dengan harga yang kompetitif dan transparan tanpa biaya tersembunyi.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-100 reveal transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                         <div class="bg-purple-100 text-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md"><svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg></div>
                        <h3 class="text-xl font-bold mb-2">Pemesanan Online</h3>
                        <p class="text-slate-600">Pesan dan lacak status laundry Anda kapan saja dan di mana saja melalui website kami.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layanan Kami Section -->
        <section id="layanan" class="py-20 bg-white">
             <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-16 reveal">Layanan Populer Kami</h2>
                 <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                     <div class="bg-white rounded-xl shadow-xl overflow-hidden group reveal">
                         <img src="https://placehold.co/600x400/e0f2fe/3b82f6?text=Cuci+Kiloan" alt="Laundry Kiloan" class="w-full h-56 object-cover transform transition-transform duration-500 group-hover:scale-110">
                         <div class="p-8"><h3 class="text-2xl font-bold mb-2">Cuci Kiloan</h3><p class="text-slate-600">Solusi praktis untuk cucian harian Anda. Cepat, bersih, dan ekonomis.</p></div>
                     </div>
                     <div class="bg-white rounded-xl shadow-xl overflow-hidden group reveal">
                         <img src="https://placehold.co/600x400/d1fae5/10b981?text=Cuci+Satuan" alt="Laundry Satuan" class="w-full h-56 object-cover transform transition-transform duration-500 group-hover:scale-110">
                         <div class="p-8"><h3 class="text-2xl font-bold mb-2">Cuci Satuan</h3><p class="text-slate-600">Perawatan khusus untuk pakaian spesial Anda seperti jas, gaun, dan kemeja.</p></div>
                     </div>
                     <div class="bg-white rounded-xl shadow-xl overflow-hidden group reveal">
                         <img src="https://placehold.co/600x400/fef3c7/f59e0b?text=Cuci+Sepatu" alt="Cuci Sepatu" class="w-full h-56 object-cover transform transition-transform duration-500 group-hover:scale-110">
                         <div class="p-8"><h3 class="text-2xl font-bold mb-2">Cuci Sepatu & Tas</h3><p class="text-slate-600">Kembalikan kilau sepatu dan tas kesayangan Anda dengan treatment khusus dari kami.</p></div>
                     </div>
                 </div>
            </div>
        </section>

        <!-- Cara Pemesanan Section (didesain ulang) -->
        <section id="cara-pesan" class="py-20">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-16 reveal">Hanya 3 Langkah Mudah</h2>
                <div class="relative">
                    <div class="hidden lg:block absolute top-12 left-0 w-full h-1 bg-blue-100"></div>
                    <div class="grid lg:grid-cols-3 gap-12 relative">
                        <div class="reveal"><div class="bg-white border-4 border-blue-500 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl font-bold text-blue-600 shadow-lg">1</div><h3 class="text-2xl font-bold mb-2">Buat Pesanan</h3><p class="text-slate-600">Daftar atau login, lalu pilih layanan dan tentukan jadwal penjemputan.</p></div>
                        <div class="reveal"><div class="bg-white border-4 border-blue-500 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl font-bold text-blue-600 shadow-lg">2</div><h3 class="text-2xl font-bold mb-2">Proses Pencucian</h3><p class="text-slate-600">Kami akan memproses pakaian Anda dengan teliti sesuai standar kualitas terbaik kami.</p></div>
                        <div class="reveal"><div class="bg-white border-4 border-blue-500 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl font-bold text-blue-600 shadow-lg">3</div><h3 class="text-2xl font-bold mb-2">Pakaian Siap Diantar</h3><p class="text-slate-600">Setelah selesai, pakaian bersih dan wangi siap diantar kembali ke lokasi Anda.</p></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-20 bg-white">
            <div class="container mx-auto px-6 max-w-4xl">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 reveal">Pertanyaan yang Sering Diajukan</h2>
                <div class="space-y-4">
                     <div class="bg-white rounded-xl shadow-md reveal transition-all duration-300 hover:shadow-lg" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex justify-between items-center text-left p-6"><span class="font-semibold text-lg">Berapa lama proses pengerjaan laundry?</span><svg :class="{ 'transform rotate-180': open }" class="w-6 h-6 text-blue-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                        <div x-show="open" x-transition class="p-6 pt-0 text-slate-600"><p>Proses standar kami adalah 2-3 hari. Namun, kami juga menyediakan layanan ekspres 1 hari dengan biaya tambahan.</p></div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md reveal transition-all duration-300 hover:shadow-lg" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex justify-between items-center text-left p-6"><span class="font-semibold text-lg">Apakah ada layanan antar-jemput?</span><svg :class="{ 'transform rotate-180': open }" class="w-6 h-6 text-blue-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                        <div x-show="open" x-transition class="p-6 pt-0 text-slate-600"><p>Tentu saja! Kami menyediakan layanan antar-jemput gratis untuk area tertentu dengan minimum order. Silakan cek cakupan area kami saat membuat pesanan.</p></div>
                    </div>
                     <div class="bg-white rounded-xl shadow-md reveal transition-all duration-300 hover:shadow-lg" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex justify-between items-center text-left p-6"><span class="font-semibold text-lg">Bagaimana cara pembayarannya?</span><svg :class="{ 'transform rotate-180': open }" class="w-6 h-6 text-blue-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                        <div x-show="open" x-transition class="p-6 pt-0 text-slate-600"><p>Anda bisa membayar secara online melalui transfer bank, e-wallet, atau bayar tunai (Cash on Delivery) saat pakaian Anda diantar kembali.</p></div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-800 text-white">
        <div class="container mx-auto px-6 py-12">
            <div class="grid md:grid-cols-3 gap-8">
                @if($profil)
                <div><h3 class="text-xl font-bold mb-4">{{ $profil->nama_laundry }}</h3><p class="text-slate-400">{{ $profil->alamat }}</p><p class="text-slate-400">{{ $profil->email }}</p><p class="text-slate-400">{{ $profil->nomor_telepon }}</p></div>
                @endif
                <div><h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3><ul class="space-y-2"><li><a href="#layanan" class="text-slate-400 hover:text-white transition-colors">Layanan</a></li><li><a href="#cara-pesan" class="text-slate-400 hover:text-white transition-colors">Cara Pesan</a></li><li><a href="#faq" class="text-slate-400 hover:text-white transition-colors">FAQ</a></li><li><a href="#" class="text-slate-400 hover:text-white transition-colors">Kebijakan Privasi</a></li></ul></div>
                <div><h3 class="text-lg font-semibold mb-4">Ikuti Kami</h3><div class="flex space-x-4"><a href="#" class="text-slate-400 hover:text-white transition-colors"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.494v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg></a><a href="#" class="text-slate-400 hover:text-white transition-colors"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.585-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.585-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.585.069-4.85c.149-3.225 1.664-4.771 4.919 4.919 1.266-.058 1.644-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.059-1.281.073-1.689.073-4.948s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98-1.281-.058-1.689-.072-4.948-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.79 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a></div></div>
            </div>
            <div class="border-t border-slate-700 mt-8 pt-6 text-center text-slate-400 text-sm">&copy; {{ date('Y') }} {{ $profil->nama_laundry ?? 'CleanFast Laundry' }}. All Rights Reserved.</div>
        </div>
    </footer>
    
    <script src="https://unpkg.com/typed.js@2.0.12"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });

            // [BARU] Typed.js initialization
            if (document.getElementById('typed-text')) {
                var typed = new Typed('#typed-text', {
                    strings: ['Pakaian Harian Anda.', 'Jas & Kemeja Kantor.', 'Sepatu Kesayangan Anda.'],
                    typeSpeed: 50,
                    backSpeed: 30,
                    backDelay: 2000,
                    loop: true
                });
            }

            // [BARU] Parallax effect for hero section
            const hero = document.getElementById('hero');
            window.addEventListener('scroll', () => {
                const scrollPosition = window.pageYOffset;
                // Menggerakkan background lebih lambat dari scroll
                hero.style.backgroundPositionY = scrollPosition * 0.5 + 'px';
            });

            // Intersection Observer for all animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Reveal animation
                        if (entry.target.classList.contains('reveal')) {
                            entry.target.classList.add('visible');
                        }
                        // Counter animation
                        if (entry.target.classList.contains('counter')) {
                            animateCounter(entry.target);
                            observer.unobserve(entry.target); // Animate only once
                        }
                    }
                });
            }, { threshold: 0.15 });

            // Observe all elements with .reveal or .counter
            document.querySelectorAll('.reveal, .counter').forEach(el => {
                observer.observe(el);
            });

            // [BARU] Counter animation function
            function animateCounter(counter) {
                const target = +counter.getAttribute('data-target');
                let count = 0;
                const speed = 200; // The lower the number, the faster the animation

                const updateCount = () => {
                    const increment = target / speed;
                    count += increment;

                    if (count < target) {
                        counter.innerText = Math.ceil(count);
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            }
        });
    </script>
</body>
</html>

