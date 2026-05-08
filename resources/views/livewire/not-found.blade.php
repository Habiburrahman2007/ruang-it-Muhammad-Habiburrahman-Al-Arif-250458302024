<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Found</title>
    <link rel="icon" href="{{ asset('img/newlogo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body>
    <div class="grid min-h-screen grid-cols-1 lg:grid-cols-2">
        <!-- Left Section: Text -->
        <div class="px-4py-8 flex flex-col items-center justify-center justify-self-center text-center">
            <h1 class="text-base-content text-blue-700 mb-3 text-5xl font-semibold">Ups...</h1>
            <h3 class="text-base-content mb-3 text-xl font-semibold">Sepertinya ada yang tersesat</h3>
            <h6 class="text-base-content mb-6 text-lg max-w-sm">
                Silahkan kembali ke halaman utama.
            </h6>
            <button
                class="relative flex items-center px-6 py-3 overflow-hidden font-medium transition-all bg-blue-500 rounded-md group">
                <span
                    class="absolute top-0 right-0 inline-block w-4 h-4 transition-all duration-500 ease-in-out bg-blue-700 rounded group-hover:-mr-4 group-hover:-mt-4">
                    <span
                        class="absolute top-0 right-0 w-5 h-5 rotate-45 translate-x-1/2 -translate-y-1/2 bg-white"></span>
                </span>
                <span
                    class="absolute bottom-0 rotate-180 left-0 inline-block w-4 h-4 transition-all duration-500 ease-in-out bg-blue-700 rounded group-hover:-ml-4 group-hover:-mb-4">
                    <span
                        class="absolute top-0 right-0 w-5 h-5 rotate-45 translate-x-1/2 -translate-y-1/2 bg-white"></span>
                </span>
                <span
                    class="absolute bottom-0 left-0 w-full h-full transition-all duration-500 ease-in-out delay-200 -translate-x-full bg-blue-600 rounded-md group-hover:translate-x-0"></span>
                <span
                    class="relative w-full text-left text-white transition-colors duration-200 ease-in-out group-hover:text-white"><a href="{{ route('dashboard') }}">Kembali ke dashboard</a></span>
            </button>
        </div>
        <!-- Right Section: Illustration -->
        <div class="relative max-h-screen w-full p-2 max-lg:hidden">
            <img src="https://cdn.flyonui.com/fy-assets/blocks/marketing-ui/404/error-5.png" alt="404 background"
                class="h-full w-full rounded-2xl" />
            <img src="https://cdn.flyonui.com/fy-assets/blocks/marketing-ui/404/error-6.png" alt="404 illustration"
                class="absolute top-1/2 left-1/2 h-[clamp(300px,40vw,477px)] -translate-x-[42%] -translate-y-1/2" />
        </div>
    </div>
</body>

</html>
