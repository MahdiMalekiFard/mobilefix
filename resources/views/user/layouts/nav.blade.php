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

            @foreach($navbarMenu as $menu)
                @if(Arr::has($menu,'sub_menu')  )
                    @if(Arr::get($menu,'access',true))
                        <x-menu-sub :title="Arr::get($menu,'title')" :icon="Arr::get($menu,'icon')">
                            @foreach(Arr::get($menu,'sub_menu',[]) as $subMenu)
                                <x-menu-item
                                    :exact="Arr::get($subMenu,'exact',false)"
                                    :title="Arr::get($subMenu,'title')"
                                    :icon="Arr::get($subMenu,'icon')"
                                    :badge="Arr::get($subMenu,'badge')"
                                    :badge-classes="Arr::get($subMenu,'badge_classes','float-left')"
                                    :link="route(Arr::get($subMenu,'route_name'),Arr::get($subMenu,'params',[]))"/>
                            @endforeach
                        </x-menu-sub>
                    @endif
                @else
                    @if(Arr::get($menu,'access',true))
                        <x-menu-item
                            :exact="Arr::get($menu,'exact',false)"
                            :title="Arr::get($menu,'title')"
                            :icon="Arr::get($menu,'icon')"
                            :badge="Arr::get($menu,'badge')"
                            :badge-classes="Arr::get($menu,'badge_classes','float-left')"
                            :link="route(Arr::get($menu,'route_name'),Arr::get($menu,'params',[]))"/>
                    @endif
                @endif
            @endforeach

        </x-menu>
    </div>
</div>
