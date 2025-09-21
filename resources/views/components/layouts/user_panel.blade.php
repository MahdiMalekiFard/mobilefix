<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale()=='fa' ? 'rtl' : 'ltr' }}"
    class="{{ request()->routeIs('user.chat.index') ? 'h-full' : '' }}"> {{-- lock html height --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="{{ request()->routeIs('user.chat.index') ? 'h-full' : '' }}"> {{-- lock body height and kill page scroll --}}

<div x-data="{ open: false }"
     x-init="() => { $refs.contentarea && ($refs.contentarea.scrollTop = 99999999) }"
     @keydown.window.escape="open = false"
     class="{{ request()->routeIs('user.chat.index') ? 'flex h-full min-h-0 overflow-hidden' : 'flex flex-col min-h-screen' }}"
>

    {{-- Mobile nav --}}
    @include('user.layouts.nav-mobile')

    {{-- Static sidebar for desktop --}}
    @include('user.layouts.nav')

    <div class="lg:ps-72 flex flex-col flex-1 min-h-0 overflow-hidden">
        <livewire:user.shared.header />

        <main class="bg-base-300 flex-1 min-h-0">
            <div
                class="h-full min-h-0 {{$external_class ?? ''}} {{ request()->routeIs('user.chat.index') ? 'px-0' : 'md:px-4 sm:px-6 lg:px-8' }}"
                x-ref="contentarea">
                {{$slot}}
            </div>
        </main>
    </div>
</div>

{{--  TOAST area --}}
<x-toast position="toast-button toast-end"/>

@livewireScripts

{{-- Global user chat listener for real-time notifications --}}
@include('user.layouts.partials.global-chat-listener')

@stack('scripts')

</body>
</html>
