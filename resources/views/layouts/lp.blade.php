<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="{{ asset('img/logo_fp-removebg-preview.png') }}" type="image/png">

    <style>
        .headline {
            @apply text-indigo-700 text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-66.66%);
            }
        }

        .animate-scroll {
            display: flex;
            animation: scroll 15s linear infinite;
        }

        /* Global Loader Styles */
        #global-loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            z-index: 99999;
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease;
        }

        .loader-content {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 5rem;
            height: 5rem;
        }

        .loader-logo {
            width: 2.8rem;
            height: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse-logo 1.5s infinite ease-in-out;
        }

        @keyframes pulse-logo {
            0% {
                transform: translate(-50%, -50%) scale(0.9);
                opacity: 0.8;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.05);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(0.9);
                opacity: 0.8;
            }
        }
    </style>
    @livewireStyles
</head>

<body class="bg-cyan-50 text-gray-900 min-h-screen flex flex-col overflow-x-hidden padding-top: 70px;">
    <div id="global-loader">
        <div class="loader-content">
            <div role="status">
                <svg aria-hidden="true" class="w-20 h-20 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
            <img src="{{ asset('img/logo_fp-removebg-preview.png') }}" alt="Logo" class="loader-logo">
        </div>
    </div>

    {{ $slot }}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-gray-900/70', 'backdrop-blur-md', 'border-gray-700', 'shadow-lg');
                navbar.classList.remove('bg-transparent', 'border-transparent');
            } else {
                navbar.classList.add('bg-transparent', 'border-transparent');
                navbar.classList.remove('bg-gray-900/70', 'backdrop-blur-md', 'border-gray-700', 'shadow-lg');
            }
        });
    </script>

    <script>
        const texts = [
            "Update terbaru seputar teknologi",
            "Belajar IT lewat bahasa bayi",
            "Referensi praktis wawasan dunia digital"
        ];

        let index = 0;
        let charIndex = 0;
        let deleting = false;
        const el = document.getElementById("typing-text");

        function typeEffect() {
            const currentText = texts[index];

            if (!deleting) {
                el.textContent = currentText.substring(0, charIndex + 1);
                charIndex++;

                if (charIndex === currentText.length) {
                    setTimeout(() => deleting = true, 1200);
                }
            } else {
                el.textContent = currentText.substring(0, charIndex - 1);
                charIndex--;

                if (charIndex === 0) {
                    deleting = false;
                    index = (index + 1) % texts.length;
                }
            }

            setTimeout(typeEffect, deleting ? 40 : 70);
        }

        typeEffect();
    </script>

    <script>
        document.addEventListener("scroll", function() {
            const jumbotron = document.getElementById("home");
            const btn = document.getElementById("backToTopBtn");

            const jumbotronHeight = jumbotron.offsetHeight;

            if (window.scrollY > jumbotronHeight) {
                btn.classList.remove("hidden", "opacity-0", "pointer-events-none");
                btn.classList.add("opacity-100");
            } else {
                btn.classList.add("hidden", "opacity-0", "pointer-events-none");
                btn.classList.remove("opacity-100");
            }
        });
    </script>


    @livewireScripts
    <script>
        document.addEventListener('livewire:navigate', () => {
            const loader = document.getElementById('global-loader');
            if (loader) loader.style.display = 'flex';
        });

        document.addEventListener('livewire:navigated', () => {
            const loader = document.getElementById('global-loader');
            if (loader) loader.style.display = 'none';
        });
    </script>
</body>

</html>
