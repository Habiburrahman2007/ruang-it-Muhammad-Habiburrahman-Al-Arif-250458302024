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
    </style>
    @livewireStyles
</head>

<body class="bg-cyan-50 text-gray-900 min-h-screen flex flex-col overflow-x-hidden padding-top: 70px;">

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
</body>

</html>
