<div>
    <nav id="navbar" x-data="{ mobileMenuOpen: false }"
        class="bg-transparent border-b border-transparent fixed top-0 left-0 w-full z-50 transition-all duration-300 ease-in-out">
        <div class="w-full flex items-center justify-between px-8 py-4">
            <a href="{{ route('landing-page') }}" class="flex items-center space-x-3 rtl:space-x-reverse mx-3">
                <img src="{{ asset('img/logo_fp-removebg-preview.png') }}" class="h-10" alt="Logo Flowbite" />
                <span class="self-center text-3xl font-semibold whitespace-nowrap text-white">Ruang IT</span>
            </a>

            <!-- Desktop Menu -->
            <ul class="hidden md:flex space-x-8 font-medium text-white gap-10 mx-3" id="desktopMenu">
                <li><a href="#about" data-section="about" class="nav-link hover:text-blue-500 transition">Tentang</a>
                </li>
                <li><a href="#categories" data-section="categories"
                        class="nav-link hover:text-blue-500 transition">Kategori</a></li>
                <li><a href="#blogs" data-section="blogs" class="nav-link hover:text-blue-500 transition">Artikel</a>
                </li>
                <li><a href="#footer" data-section="footer" class="nav-link hover:text-blue-500 transition">Kontak</a>
                </li>
            </ul>

            <!-- Hamburger untuk mobile -->
            <div class="relative md:hidden">
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-expanded="false" aria-controls="dropdownMenu">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>

                <!-- Dropdown Menu Mobile -->
                <ul x-show="mobileMenuOpen" @click.outside="mobileMenuOpen = false"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-40 bg-gray-800 border border-gray-700 rounded-lg shadow-lg py-2"
                    style="display: none;">
                    <li><a href="#about" @click="mobileMenuOpen = false" data-section="about"
                            class="nav-link block px-4 py-2 text-white hover:bg-gray-700 hover:text-blue-500">Tentang</a>
                    </li>
                    <li><a href="#categories" @click="mobileMenuOpen = false" data-section="categories"
                            class="nav-link block px-4 py-2 text-white hover:bg-gray-700 hover:text-blue-500">Kategori</a>
                    </li>
                    <li><a href="#blogs" @click="mobileMenuOpen = false" data-section="blogs"
                            class="nav-link block px-4 py-2 text-white hover:bg-gray-700 hover:text-blue-500">Artikel</a>
                    </li>
                    <li><a href="#footer" @click="mobileMenuOpen = false" data-section="footer"
                            class="nav-link block px-4 py-2 text-white hover:bg-gray-700 hover:text-blue-500">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="home" class="relative min-h-screen flex items-center bg-cover bg-center bg-no-repeat"
        style="background-image: linear-gradient(to right, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.9) 50%, rgba(0,0,0,0) 100%), url('{{ asset('img/jumbotron.png') }}');">

        <div
            class="px-4 mx-auto max-w-screen-xl flex flex-col-reverse lg:flex-row items-center justify-between text-center lg:text-left w-full">
            <div class="mt-8 lg:mt-0 lg:w-1/2">
                <h1 class="mb-3 text-4xl tracking-tight font-extrabold text-white">
                    Jelajahi Masa Depan Teknologi
                </h1>
                <p class="mb-3 text-xl font-medium text-blue-700 h-8">
                    <span id="typing-text"></span>
                    <span class="border-r-2 border-blue-300 ml-1 animate-pulse"></span>
                </p>
                <p class="mb-8 text-lg font-normal text-gray-200 lg:text-xl">
                    Temukan artikel mendalam tentang IT, coding, perangkat lunak, keamanan siber, dan tren teknologi
                    terkini di
                    satu platform.
                </p>
                <div class="sm:w-fit w-full flex justify-center md:justify-start">
                    <button
                        class="relative overflow-hidden h-14 w-50 rounded-md bg-blue-800 p-2 flex justify-center items-center font-extrabold text-sky-50 shadow-[0px_1px_2px_0px_rgba(16,_24,_40,_0.05)] group cursor-pointer">

                        <div
                            class="absolute z-10 w-40 h-40 rounded-full bg-blue-900 transition-all duration-500 ease-in-out group-hover:scale-150">
                        </div>
                        <div
                            class="absolute z-10 w-32 h-32 rounded-full bg-blue-800 transition-all duration-500 ease-in-out group-hover:scale-150">
                        </div>
                        <div
                            class="absolute z-10 w-24 h-24 rounded-full bg-blue-700 transition-all duration-500 ease-in-out group-hover:scale-150">
                        </div>
                        <div
                            class="absolute z-10 w-16 h-16 rounded-full bg-blue-600 transition-all duration-500 ease-in-out group-hover:scale-150">
                        </div>
                        <div
                            class="absolute z-10 w-8 h-8 rounded-full bg-blue-500 transition-all duration-500 ease-in-out group-hover:scale-150">
                        </div>
                        <a href="{{ route('login') }}" class="z-10">Masuk disini</a>
                    </button>
                </div>
            </div>

            <div class="lg:w-1/2 flex justify-center lg:justify-end">
                <img src="{{ asset('img/logohero.png') }}" alt="Logo" class="w-100 lg:w-200 h-auto">
            </div>
        </div>
    </section>

    <section class="py-24 relative xl:mr-0 lg:mr-5 mr-0 bg-gray-900" id="about">
        <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
            <div class="w-full justify-start items-center xl:gap-12 gap-10 grid lg:grid-cols-2 grid-cols-1">
                <div class="w-full flex-col justify-center lg:items-start items-center gap-10 inline-flex">
                    <div class="w-full flex-col justify-center items-start gap-8 flex">
                        <div class="flex-col justify-start lg:items-start items-center gap-4 flex">
                            <div class="w-full flex-col justify-start lg:items-start items-center gap-3 flex">
                                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-indigo-700">
                                    Tentang Kami</h2>
                                <p
                                    class="text-gray-500 text-base font-normal leading-relaxed lg:text-start text-center">
                                    Temukan wawasan dan tren terbaru di dunia teknologi di sini. Dari
                                    inovasi perangkat lunak
                                    dan pengembangan aplikasi hingga pembaruan AI dan keamanan data—kami membahas
                                    semuanya dalam
                                    bahasa yang mudah dipahami dan
                                    relevan bagi mereka yang hidup di era digital. Jadilah yang pertama tahu tentang
                                    perkembangan teknologi yang membentuk masa depan.</p>
                            </div>
                        </div>
                        <div class="w-full flex flex-col justify-center items-start gap-6">
                            <div class="w-full grid gap-8 grid-cols-1 md:grid-cols-2">
                                @foreach ($stats as $stat)
                                    <div
                                        class="w-full h-full p-3.5 rounded-xl border border-gray-200 hover:border-gray-400 transition-all duration-700 ease-in-out flex flex-col justify-start items-start gap-2.5">
                                        <h4 class="text-white text-2xl font-bold font-manrope leading-9">
                                            {{ $stat['title'] }}
                                        </h4>
                                        <p class="text-gray-500 text-base font-normal leading-relaxed">
                                            {{ $stat['desc'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <div class="sm:w-fit w-full flex justify-center md:justify-start">
                        <button
                            class="relative overflow-hidden h-14 w-50 rounded-md bg-blue-800 p-2 flex justify-center items-center font-extrabold text-sky-50 shadow-[0px_1px_2px_0px_rgba(16,_24,_40,_0.05)] group cursor-pointer">

                            <div
                                class="absolute z-10 w-40 h-40 rounded-full bg-blue-900 transition-all duration-500 ease-in-out group-hover:scale-150">
                            </div>
                            <div
                                class="absolute z-10 w-32 h-32 rounded-full bg-blue-800 transition-all duration-500 ease-in-out group-hover:scale-150">
                            </div>
                            <div
                                class="absolute z-10 w-24 h-24 rounded-full bg-blue-700 transition-all duration-500 ease-in-out group-hover:scale-150">
                            </div>
                            <div
                                class="absolute z-10 w-16 h-16 rounded-full bg-blue-600 transition-all duration-500 ease-in-out group-hover:scale-150">
                            </div>
                            <div
                                class="absolute z-10 w-8 h-8 rounded-full bg-blue-500 transition-all duration-500 ease-in-out group-hover:scale-150">
                            </div>
                            <a href="{{ route('guest') }}" class="z-10">Baca disini</a>
                        </button>
                    </div>


                </div>
                <div class="w-full lg:justify-start justify-center items-start flex">
                    <div
                        class="sm:w-[564px] w-full sm:h-[646px] h-full sm:bg-white rounded-3xl sm:border border-gray-200 relative">
                        <img class="sm:mt-5 sm:ml-5 w-full h-full rounded-3xl object-cover"
                            src="{{ asset('img/eye tech.jpeg') }}" alt="Gambar Tentang Kami" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="flex-1 container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <section class="mb-16" id="categories">
            <h2 class="mb-10 text-4xl text-center tracking-tight font-extrabold text-indigo-700">Kategori Populer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">

                <div
                    class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <a href="#">
                        <img class="rounded-t-lg"
                            src="{{ asset('img/Download Desktop source code and Wallpaper by coding and programming_ for free.jpeg') }}"
                            alt="" />
                    </a>
                    <div class="p-5">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Front End</h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Penjelajahan mendalam tentang
                            berbagai
                            framework dengan beberapa bahasa.</p>
                    </div>
                </div>

                <div
                    class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <a href="#">
                        <img class="rounded-t-lg"
                            src="{{ asset('img/6 Ways to Have Cyber Security in Your Business.jpeg') }}"
                            alt="" />
                    </a>
                    <div class="p-5">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Keamanan Siber</h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Tetap aman dengan intelijen
                            ancaman dan praktik terbaik terkini.</p>
                    </div>
                </div>

                <div
                    class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <a href="#">
                        <img class="rounded-t-lg"
                            src="{{ asset('img/Futuristic Cloud Computing Concept_ AI, Big Data & Technology Innovation, cloud computing concept Stock Photo _ Adobe Stock.jpeg') }}"
                            alt="" />
                    </a>
                    <div class="p-5">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Jaringan</h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Jelajahi AWS, Azure, Google Cloud,
                            dan arsitektur tanpa server.</p>
                        </a>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <div class="bg-gray-900 py-24 sm:py-32" id="blogs">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-indigo-700">Dari Artikel</h2>
                <p class="mt-2 text-lg text-gray-300">
                    Pelajari cara mengembangkan skill IT Anda dengan saran ahli kami.
                </p>
            </div>

            <!-- Container scroll horizontal -->
            <div class="relative mt-10 overflow-hidden">
                <div class="flex animate-scroll gap-x-8">
                    @foreach ($articles as $article)
                        <a href="{{ auth()->check() ? route('detail-article', $article->slug) : route('detail-article-guest', $article->slug) }}"
                            class="block cursor-pointer">
                            <article class="flex-none w-80 bg-gray-800 rounded-xl p-5 text-white shadow-lg">
                                <div class="flex items-center gap-x-4 text-xs mb-3">
                                    <time datetime="{{ $article->created_at->toDateString() }}"
                                        class="text-gray-400">
                                        {{ $article->created_at->format('M d, Y') }}
                                    </time>
                                    @if ($article->category->name)
                                        <span
                                            class="rounded-full bg-gray-700 px-2 py-1 text-gray-300 text-xs font-medium">
                                            {{ $article->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg font-semibold mb-2">{{ $article->title }}</h3>
                                @php
                                    $preview = \App\Helpers\ContentHelper::excerpt($article->content, 120);
                                @endphp
                                <p class="card-text text-secondary mb-4">{!! $preview !!}</p>

                                <div class="flex items-center mt-4 gap-x-3">
                                    <img src="{{ $article->user->photo_profile ?? 'https://via.placeholder.com/40' }}"
                                        alt="Penulis" class="w-10 h-10 rounded-full object-cover" />
                                    <div class="text-sm">
                                        <p class="font-semibold">{{ $article->user->name ?? 'Penulis Tidak Dikenal' }}
                                        </p>
                                        <p class="text-gray-400">{{ $article->user->profession ?? 'Kontributor' }}</p>
                                    </div>
                                </div>
                            </article>
                        </a>
                    @endforeach
                    <!-- Duplicate untuk loop halus -->
                    @foreach ($articles as $article)
                        <a href="{{ auth()->check() ? route('detail-article', $article->slug) : route('detail-article-guest', $article->slug) }}"
                            class="block cursor-pointer">
                            <article class="flex-none w-80 bg-gray-800 rounded-xl p-5 text-white shadow-lg">
                                <div class="flex items-center gap-x-4 text-xs mb-3">
                                    <time datetime="{{ $article->created_at->toDateString() }}"
                                        class="text-gray-400">
                                        {{ $article->created_at->format('M d, Y') }}
                                    </time>
                                    @if ($article->category->name)
                                        <span
                                            class="rounded-full bg-gray-700 px-2 py-1 text-gray-300 text-xs font-medium">
                                            {{ $article->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg font-semibold mb-2">{{ $article->title }}</h3>
                                <p class="text-sm text-gray-300 line-clamp-3">
                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                </p>

                                <div class="flex items-center mt-4 gap-x-3">
                                    <img src="{{ $article->user->photo_profile ?? 'https://via.placeholder.com/40' }}"
                                        alt="Penulis" class="w-10 h-10 rounded-full object-cover" />
                                    <div class="text-sm">
                                        <p class="font-semibold">{{ $article->user->name ?? 'Penulis Tidak Dikenal' }}
                                        </p>
                                        <p class="text-gray-400">{{ $article->user->profession ?? 'Kontributor' }}</p>
                                    </div>
                                </div>
                            </article>
                        </a>
                    @endforeach
                    <!-- Duplicate ketiga untuk loop yang lebih smooth -->
                    @foreach ($articles as $article)
                        <a href="{{ auth()->check() ? route('detail-article', $article->slug) : route('detail-article-guest', $article->slug) }}"
                            class="block cursor-pointer">
                            <article class="flex-none w-80 bg-gray-800 rounded-xl p-5 text-white shadow-lg">
                                <div class="flex items-center gap-x-4 text-xs mb-3">
                                    <time datetime="{{ $article->created_at->toDateString() }}"
                                        class="text-gray-400">
                                        {{ $article->created_at->format('M d, Y') }}
                                    </time>
                                    @if ($article->category->name)
                                        <span
                                            class="rounded-full bg-gray-700 px-2 py-1 text-gray-300 text-xs font-medium">
                                            {{ $article->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg font-semibold mb-2">{{ $article->title }}</h3>
                                <p class="text-sm text-gray-300 line-clamp-3">
                                    {{ Str::limit(strip_tags($article->content), 120) }}
                                </p>

                                <div class="flex items-center mt-4 gap-x-3">
                                    <img src="{{ $article->user->photo_profile ?? 'https://via.placeholder.com/40' }}"
                                        alt="Penulis" class="w-10 h-10 rounded-full object-cover" />
                                    <div class="text-sm">
                                        <p class="font-semibold">{{ $article->user->name ?? 'Penulis Tidak Dikenal' }}
                                        </p>
                                        <p class="text-gray-400">{{ $article->user->profession ?? 'Kontributor' }}</p>
                                    </div>
                                </div>
                            </article>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <section class="bg-white">
        <div
            class="gap-8 items-center py-8 px-4 mx-auto max-w-screen-xl xl:gap-16 md:grid md:grid-cols-2 sm:py-16 lg:px-6">
            <img class="w-full h-150 rounded-3xl object-cover" src="{{ asset('img/gambar lp.png') }}"
                alt="gambar dasbor">
            <div class="mt-4 md:mt-0">
                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-indigo-700">Membangun Wawasan Digital
                    bersama
                    Ruang IT</h2>
                <p class="mb-6 font-light text-gray-900 md:text-lg">Ruang IT menyajikan wawasan, tren,
                    dan pembaruan terkini dari dunia teknologi. Temukan artikel tentang pengembangan perangkat lunak,
                    inovasi AI,
                    keamanan siber,
                    dan kiat coding yang relevan bagi mahasiswa dan profesional IT. Jelajahi informasi terbaru dan
                    menjadi
                    bagian dari komunitas yang berkembang di era digital.</p>
                <div class="sm:w-fit w-full flex justify-center md:justify-start">
                    <button
                        class="relative border hover:border-sky-600 duration-500 group cursor-pointer text-sky-50  overflow-hidden h-14 w-50 rounded-md bg-blue-800 p-2 flex justify-center items-center font-extrabold">
                        <div
                            class="absolute z-10 w-40 h-40 rounded-full group-hover:scale-150 transition-all  duration-500 ease-in-out bg-blue-900 delay-150 group-hover:delay-75">
                        </div>
                        <div
                            class="absolute z-10 w-32 h-32 rounded-full group-hover:scale-150 transition-all  duration-500 ease-in-out bg-blue-800 delay-150 group-hover:delay-100">
                        </div>
                        <div
                            class="absolute z-10 w-24 h-24 rounded-full group-hover:scale-150 transition-all  duration-500 ease-in-out bg-blue-700 delay-150 group-hover:delay-150">
                        </div>
                        <div
                            class="absolute z-10 w-16 h-16 rounded-full group-hover:scale-150 transition-all  duration-500 ease-in-out bg-blue-600 delay-150 group-hover:delay-200">
                        </div>
                        <div
                            class="absolute z-10 w-8 h-8 rounded-full group-hover:scale-150 transition-all  duration-500 ease-in-out bg-blue-500 delay-150 group-hover:delay-300">
                        </div>
                        <p class="z-10"><a href="{{ route('register') }}">Daftar disini</a></p>
                    </button>
                </div>

            </div>
        </div>
    </section>

    <a href="#home" id="backToTopBtn" class="hidden opacity-0 pointer-events-none transition-opacity duration-300">
        <button
            class="fixed bottom-6 right-6 
        flex justify-center items-center shadow-lg 
        bg-blue-500 text-white w-14 h-14 rounded-full 
        hover:bg-blue-600 transition-all duration-300 ease-out 
        group z-50 hover:scale-105 active:scale-95">
            <i class="fa-solid fa-arrow-up"></i>
            <span class="absolute inset-0 rounded-full border-4 border-white/30 scale-100 animate-pulse"></span>
            <div
                class="absolute right-full mr-3 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap">
                <div class="bg-gray-800 text-white text-sm px-3 py-1 rounded shadow-lg">
                    Back to top
                </div>
                <div
                    class="absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2 rotate-45 w-2 h-2 bg-gray-800">
                </div>
            </div>
        </button>
    </a>

    <footer class="bg-gray-900 text-gray-300 py-10" id="footer">
        <div class="max-w-screen-xl mx-auto text-center space-y-6">
            <div class="flex justify-center items-center gap-3">
                <img src="{{ asset('img/logo_fp-removebg-preview.png') }}" alt="Logo" class="w-12 h-auto">
                <h1 class="text-white text-xl font-semibold">Ruang IT</h1>
            </div>
            <h6>Tempat Belajar IT untuk Semua Kalangan</h6>
            <ul class="flex justify-center gap-6 text-sm">
                <li><a href="#home" class="hover:text-white transition">Beranda</a></li>
                <li><a href="#about" class="hover:text-white transition">Tentang</a></li>
                <li><a href="#categories" class="hover:text-white transition">Kategori</a></li>
                <li><a href="#blogs" class="hover:text-white transition">Blog</a></li>
                <li><a href="#footer" class="hover:text-white transition">Kontak</a></li>
            </ul>

            <div class="flex justify-center gap-8">
                <a href="https://www.tiktok.com/@hbbrrhmn_07?lang=id-ID" target="blank"
                    class="w-9 h-9 flex items-center justify-center rounded-full border border-white text-white hover:bg-white hover:text-black transition">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
                <a href="https://www.youtube.com/channel/UCHz4lCB-Z43Sp12BtA28Slw" target="blank"
                    class="w-9 h-9 flex items-center justify-center rounded-full border border-red-600 text-red-600 hover:bg-red-600 hover:text-black transition">
                    <i class="fa-brands fa-youtube"></i>
                </a>
                <a href="https://wa.link/zjo1b2" target="blank"
                    class="w-9 h-9 flex items-center justify-center rounded-full border border-green-400 text-green-400 hover:bg-green-400 hover:text-black transition">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="https://www.instagram.com/_hbbrhmn.ofc/" target="blank"
                    class="w-9 h-9 flex items-center justify-center rounded-full border border-pink-500 text-pink-500 hover:bg-pink-500 hover:text-black transition">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </div>

            <p class="text-xs text-gray-500">
                Hak Cipta ©2025 Dilindungi undang-undang | Dibuat sebagai tugas akhir semester 1 di Politeknik IDN
            </p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all elements with id (section, div, footer, etc.)
            const sections = document.querySelectorAll('[id]');
            const navLinks = document.querySelectorAll('.nav-link');

            function setActiveLink() {
                let currentSection = '';
                const scrollPosition = window.scrollY + 150; // Offset untuk navbar

                // Check if we're at the bottom of the page
                const isBottom = (window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight -
                    100;

                sections.forEach((section, index) => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute('id');

                    // Skip elements without proper ids or that aren't navigation targets
                    if (!sectionId || !['home', 'about', 'categories', 'blogs', 'footer'].includes(
                            sectionId)) {
                        return;
                    }

                    // If at bottom, highlight footer
                    if (isBottom && sectionId === 'footer') {
                        currentSection = 'footer';
                        return;
                    }

                    // Normal scroll detection
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        currentSection = sectionId;
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('text-blue-500');
                    const section = link.getAttribute('data-section');

                    if (section === currentSection) {
                        link.classList.add('text-blue-500');
                    }
                });
            }

            // Run on scroll
            window.addEventListener('scroll', setActiveLink);

            // Run on page load
            setActiveLink();

            // Hamburger menu toggle removed (replaced by Alpine.js)
        });
    </script>
</div>
