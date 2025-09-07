<!DOCTYPE html>
<html
        lang="{{ str_replace('_', '-', app()->getLocale()) }}"
        dir="{{app()->getLocale()=='fa'?'rtl':'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="">

<div x-data="{ open: false }"
     x-init="() => { $refs.contentarea.scrollTop = 99999999 }"
     @keydown.window.escape="open = false" class="flex flex-col min-h-screen">

    @include('admin.layouts.navbar-mobile')


    <!-- Static sidebar for desktop -->
    @include('admin.layouts.navbar')

    <div class="lg:ps-72 flex flex-col flex-1">
        <livewire:admin.shared.header />
        <main class="bg-base-300 h-full flex-1">
            <div
                x-ref="contentarea"
                class="h-full {{ $external_class ?? '' }} {{ request()->routeIs('admin.chat.index') ? 'px-0' : 'md:px-4 sm:px-6 lg:px-8' }}"
            >
                {{ $slot }}
            </div>
        </main>
    </div>



    {{--    <footer class="footer sm:footer-horizontal footer-center bg-base-300 text-base-content p-4">--}}
    {{--        <aside>--}}
    {{--            <p>Copyright © {new Date().getFullYear()} - All right reserved by ACME Industries Ltd</p>--}}
    {{--        </aside>--}}
    {{--    </footer>--}}
</div>

{{--  TOAST area --}}
<x-toast position="toast-button toast-end"/>

@livewireScripts

</body>
</html>
