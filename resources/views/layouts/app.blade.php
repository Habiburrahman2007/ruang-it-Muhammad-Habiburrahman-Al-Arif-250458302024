<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="shortcut icon" href="{{ asset('dist/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/table-datatable.css') }}">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-navigate-once="true"></script>
    <script src="{{ asset('js/alerts.js') }}" data-navigate-once="true"></script>
</head>

<body>

    <script src="{{ asset('dist/assets/static/js/initTheme.js') }}" data-navigate-once="true"></script>
    <div id="app">
        <div id="global-loader">
            <div class="loader-content">
                <div class="spinner-border text-primary" role="status" style="width: 5rem; height: 5rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <img src="{{ asset('img/logo_fp-removebg-preview.png') }}" alt="Logo" class="loader-logo">
            </div>
        </div>

        @include('layouts.sidebar')

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="content-wrapper container">
                {{ $slot }}
            </div>

        </div>
    </div>


    @stack('scripts')

    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <script>
        window.article_created = @json(session('article_created'));
        window.category_created = @json(session('category_created'));
        window.article_deleted = @json(session('article_deleted'));
        window.sessionSuccess = @json(session('success'));
        window.article_updated = @json(session('article_updated'));
        window.profile_updated = @json(session('profile_updated'));
        window.comment_updated = @json(session('comment_updated'));
    </script>
    <script src="{{ asset('js/custom.js') }}" data-navigate-once="true"></script>
    @livewireScripts

</body>

</html>
