<div
    class="flex max-w-md bg-gray-800/50 backdrop-blur-md rounded-3xl shadow-lg overflow-hidden my-5 mx-auto relative border border-blue-700">
    <a href="{{ url('/') }}"
        class="absolute top-0 left-0 z-10 flex items-center gap-2 px-6 py-3 text-sm font-medium text-blue-400 transition-colors bg-gray-800/50 border-r border-b border-blue-700 rounded-br-3xl hover:bg-gray-800 hover:text-blue-300">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Beranda</span>
    </a>
    <div class="w-full p-6">
        <!-- Header -->
        <div class="text-center mb-5">
            <img src="{{ asset('img/logo_fp-removebg-preview.png') }}" alt="Logo" class="mx-auto h-20 w-auto" />
            <h2 class="mt-3 text-2xl font-bold text-white">Silahkan masuk ke akun Anda</h2>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="login" class="space-y-5">
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-200">Email</label>
                <input type="email" id="email" wire:model="email"
                    class="w-full p-2.5 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required />
                <small class="text-gray-400">Contoh: fulan@gmail.com</small>
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="relative mb-10">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-200">
                    Kata sandi
                </label>
                <input id="password" type="password" wire:model="password"
                    class="w-full p-2.5 rounded-lg border border-gray-600 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required />
                <button type="button" id="togglePassword"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-300 mt-7">
                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                </button>
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-center items-center">
                <button type="submit" wire:click="login"
                    class="overflow-hidden w-32 h-10 bg-blue-700 text-white border-none rounded-md text-xl font-bold cursor-pointer relative flex justify-center items-center group"
                    wire:loading.attr="disabled">

                    {{-- Text default --}}
                    <span class="relative z-10 transition-opacity duration-300 group-hover:opacity-0"
                        wire:loading.remove wire:target="login">
                        Masuk
                    </span>

                    {{-- Text hover --}}
                    <span class="absolute z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        wire:loading.remove wire:target="login">
                        Tekan aku
                    </span>

                    {{-- SPINNER FontAwesome --}}
                    <span class="absolute z-10 text-white" wire:loading wire:target="login">
                        <i class="fa-solid fa-spinner fa-spin text-lg"></i>
                    </span>

                    {{-- Animation layers --}}
                    <span
                        class="absolute w-36 h-32 -top-8 -left-2 bg-white rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-500 duration-1000 origin-left z-0"></span>
                    <span
                        class="absolute w-36 h-32 -top-8 -left-2 bg-blue-400 rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-700 duration-700 origin-left z-0"></span>
                    <span
                        class="absolute w-36 h-32 -top-8 -left-2 bg-blue-600 rotate-x-0 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-1000 duration-500 origin-left z-0"></span>
                </button>

            </div>


        </form>

        <p class="mt-6 text-center text-white text-sm">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-300">Daftar
                disini.</a><br>
            Hubungi <a href="https://wa.link/zjo1b2" target="blank"
                class="text-blue-600 font-semibold hover:text-blue-300">admin</a> jika punya pertanyaan.
        </p>
    </div>
</div>
