<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale()==='fa'?'rtl':'ltr' }}"
    class="h-full">  {{-- removed 'light' --}}
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Authentication</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        (function () {
            try {
                const theme = localStorage.getItem('mx-theme')?.replace(/"/g,'') || 'light';
                const klass = localStorage.getItem('mx-class')?.replace(/"/g,'') || 'light';
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.classList.remove('light','dark');
                document.documentElement.classList.add(klass);
            } catch (_) {}
        })();
    </script>
</head>
<body class="h-full">
{{-- background --}}
<div class="absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute inset-y-0 right-0 w-full bg-[#3b82f6]/10 ring-2 ring-[#3b82f6]/20 dark:bg-[#0b1220]"></div>
    <svg class="absolute inset-0 h-full w-full stroke-[#3b82f6]/30 dark:stroke-white/10
                [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
         aria-hidden="true">
        <defs>
            <pattern id="grid" width="200" height="200" x="100%" y="-1" patternUnits="userSpaceOnUse">
                <path d="M130 200V.5M.5 .5H200" fill="none"></path>
            </pattern>
        </defs>
        <rect width="100%" height="100%" stroke-width="0" fill="white" class="dark:fill-[#0b1220]"></rect>
        <svg x="100%" y="-1" class="overflow-visible fill-[#3b82f6]/10 dark:fill-white/[0.04]">
            <path d="M-470.5 0h201v201h-201Z" stroke-width="0"></path>
        </svg>
        <rect width="100%" height="100%" stroke-width="0" fill="url(#grid)"></rect>
    </svg>
</div>

<div class="flex min-h-screen items-center justify-center px-6 py-12 lg:px-8">
    <div class="w-fit lg:w-4/12 px-8 py-8 rounded-2xl border shadow-xl
            bg-white/90 dark:bg-gray-900/85
            border-gray-200 dark:border-gray-700
            backdrop-blur-sm text-gray-900 dark:text-gray-100 !opacity-100
            [color-scheme:light] dark:[color-scheme:dark]">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
            <h2 class="mt-8 text-center text-2xl font-bold tracking-tight
                   text-gray-900 dark:text-gray-100
                   antialiased dark:drop-shadow-[0_1px_0_rgba(0,0,0,0.6)]">
                Login to the admin panel
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            {{ $slot }}
        </div>
    </div>
</div>

@livewireScripts
</body>
</html>
