<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col bg-gray-900 dark:bg-base-100">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-4">
        <div class="flex h-16 shrink-0 items-center">
            <img class="h-8 w-auto"
                 src="{{ asset('assets/images/logo/logo-light.png') }}"
                 alt="Your Company">
        </div>
        <x-menu activate-by-route active-bg-color="bg-gray-800 dark:bg-gray-800 text-white hover:text-white"
                class="!p-0 !text-white">

            @include('admin.layouts.partials.menu-tree', ['items' => $navbarMenu])

        </x-menu>
    </div>
</div>
