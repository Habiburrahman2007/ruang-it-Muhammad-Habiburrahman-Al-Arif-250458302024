<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>{{ $title }}</title>
    <link rel="icon" href="{{ asset('img/logo_fp-removebg-preview.png') }}" type="image/png">
    <style>
        body.loading-screen * {
            cursor: wait !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-navigate-once="true"></script>
</head>

<body class="w-full">
    <div class="flex flex-col justify-center items-center min-h-screen py-5 px-4 w-full bg-cover bg-center"
        style="background-image: url('{{ asset('img/bg-auth.jpeg') }}');">
        {{ $slot }}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function setupPasswordToggle(inputId, toggleButtonId, iconId) {
                const input = document.getElementById(inputId);
                const toggleButton = document.getElementById(toggleButtonId);
                const icon = document.getElementById(iconId);

                if (!input || !toggleButton || !icon) return;

                icon.classList.toggle('fa-eye', input.type === 'text');
                icon.classList.toggle('fa-eye-slash', input.type === 'password');

                toggleButton.addEventListener('click', () => {
                    const newType = input.type === 'password' ? 'text' : 'password';
                    input.type = newType;
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            setupPasswordToggle('password', 'togglePassword', 'eyeIcon');
            setupPasswordToggle('confirm-password', 'toggleConfirmPassword', 'eyeIconConfirm');

        });
    </script>



    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('loginFailed', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Email atau password salah!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Coba lagi'
                })
            });
        });
    </script>
    <script>
        function showSpinner(btn) {
            const spinner = btn.querySelector('.spinner');
            const text = btn.querySelector('.button-text');
            spinner.classList.remove('hidden');
            text.classList.add('hidden');
        }
    </script>

</body>

</html>
