<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-b-gray-700  px-4 shadow-xs sm:gap-x-6 sm:px-6 lg:px-8">
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="open = true">
        <span class="sr-only">Open sidebar</span>
        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
        </svg>
    </button>

    <!-- Separator -->
    {{--    <div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true"></div>--}}
    <x-menu-separator/>
    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <div
            class="relative flex flex-1 items-center max-w-4xl"
            x-data="{ searchOpen: false }"
            @click.outside="searchOpen = false; $wire.set('search', '')"
            @keydown.escape.window="searchOpen = false; $wire.set('search', '')"
        >
            <form class="relative w-full" wire:submit="submitSearch" autocomplete="off">
                <input
                    type="text"
                    name="search"
                    aria-label="Search"
                    autocomplete="off"
                    autocorrect="off"
                    autocapitalize="off"
                    spellcheck="false"
                    wire:model.live.debounce.350ms="search"
                    @focus="searchOpen = true"
                    @input="searchOpen = true"
                    class="h-10 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-base-200 ps-10 pe-10 text-sm text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 placeholder:text-gray-400"
                    placeholder="Suche: Bestellung, Nutzer, Adresse, Kategorie, Blog, Service, Opinions, FAQ"
                >
                @if(mb_strlen(trim($search)) > 0)
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        @click="searchOpen = false"
                        class="absolute end-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        aria-label="Clear search"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                @endif
                <button type="submit" class="sr-only">Search</button>
                <svg class="pointer-events-none absolute start-3 top-1/2 -translate-y-1/2 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"></path>
                </svg>
            </form>

            <div x-show="searchOpen" class="absolute top-full start-0 mt-1 w-full bg-white dark:bg-base-100 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden z-50">
                <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-700 bg-gray-50/80 dark:bg-base-200/40">
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        <button type="button" wire:click="setSearchScope('all')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'all' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">All</button>
                        <button type="button" wire:click="setSearchScope('orders')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'orders' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">Orders</button>
                        <button type="button" wire:click="setSearchScope('users')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'users' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">Users</button>
                        <button type="button" wire:click="setSearchScope('addresses')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'addresses' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">Addresses</button>
                        <button type="button" wire:click="setSearchScope('categories')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'categories' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">Categories</button>
                        <button type="button" wire:click="setSearchScope('blogs')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'blogs' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">Blogs</button>
                        <button type="button" wire:click="setSearchScope('services')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'services' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">Services</button>
                        <button type="button" wire:click="setSearchScope('opinions')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'opinions' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">Opinions</button>
                        <button type="button" wire:click="setSearchScope('faqs')" class="shrink-0 cursor-pointer px-3 py-1.5 rounded-full text-xs font-semibold transition {{ $searchScope === 'faqs' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-base-100 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-base-300/40' }}">FAQ</button>
                    </div>
                </div>
                <div class="max-h-[60vh] overflow-y-auto">
                @if(mb_strlen(trim($search)) >= 2)
                    @if($this->orderResults->isNotEmpty())
                        <div class="px-4 pt-3 pb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Orders</div>
                        @foreach($this->orderResults as $result)
                            <a
                                href="{{ route('admin.order.show', ['order' => $result->id]) }}"
                                class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition"
                            >
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">#{{ $result->order_number }}</p>
                                    <p class="text-xs text-gray-500">Order ID: {{ $result->id }} • {{ $result->updated_at?->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ number_format((float) $result->total, 0) }}</span>
                            </a>
                        @endforeach
                    @endif

                    @if($this->userResults->isNotEmpty())
                        <div class="px-4 pt-3 pb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t border-gray-100 dark:border-gray-700">Users</div>
                        @foreach($this->userResults as $userResult)
                            <a
                                href="{{ route('admin.user.edit', ['user' => $userResult->id]) }}"
                                class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition"
                            >
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $userResult->name ?: 'User #' . $userResult->id }}</p>
                                <p class="text-xs text-gray-500 truncate">ID: {{ $userResult->id }} • {{ $userResult->mobile ?: ($userResult->email ?: '-') }}</p>
                            </a>
                        @endforeach
                    @endif

                    @if($this->addressResults->isNotEmpty())
                        <div class="px-4 pt-3 pb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t border-gray-100 dark:border-gray-700">Addresses</div>
                        @foreach($this->addressResults as $addressResult)
                            <a
                                href="{{ route('admin.address.edit', ['address' => $addressResult->id]) }}"
                                class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition"
                            >
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $addressResult->title ?: 'Address #' . $addressResult->id }}</p>
                                <p class="text-xs text-gray-500 truncate">ID: {{ $addressResult->id }} • {{ $addressResult->address }}</p>
                            </a>
                        @endforeach
                    @endif

                    @if($this->categoryResults->isNotEmpty())
                        <div class="px-4 pt-3 pb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t border-gray-100 dark:border-gray-700">Categories</div>
                        @foreach($this->categoryResults as $categoryResult)
                            <a
                                href="{{ route('admin.category.edit', ['category' => $categoryResult->id]) }}"
                                class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition"
                            >
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $categoryResult->title ?: ('Category #' . $categoryResult->id) }}</p>
                                <p class="text-xs text-gray-500 truncate">ID: {{ $categoryResult->id }} • {{ $categoryResult->slug }}</p>
                            </a>
                        @endforeach
                    @endif

                    @if($this->blogResults->isNotEmpty())
                        <div class="px-4 pt-3 pb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t border-gray-100 dark:border-gray-700">Blogs</div>
                        @foreach($this->blogResults as $blogResult)
                            <a
                                href="{{ route('admin.blog.edit', ['blog' => $blogResult->id]) }}"
                                class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition"
                            >
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $blogResult->title ?: ('Blog #' . $blogResult->id) }}</p>
                                <p class="text-xs text-gray-500 truncate">ID: {{ $blogResult->id }} • {{ $blogResult->slug }}</p>
                            </a>
                        @endforeach
                    @endif

                    @if($this->serviceResults->isNotEmpty())
                        <div class="px-4 pt-3 pb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t border-gray-100 dark:border-gray-700">Services</div>
                        @foreach($this->serviceResults as $serviceResult)
                            <a
                                href="{{ route('admin.service.edit', ['service' => $serviceResult->id]) }}"
                                class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition"
                            >
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $serviceResult->title ?: ('Service #' . $serviceResult->id) }}</p>
                                <p class="text-xs text-gray-500 truncate">ID: {{ $serviceResult->id }} • {{ $serviceResult->slug }}</p>
                            </a>
                        @endforeach
                    @endif

                    @if($this->opinionResults->isNotEmpty())
                        <div class="px-4 pt-3 pb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t border-gray-100 dark:border-gray-700">Opinions</div>
                        @foreach($this->opinionResults as $opinionResult)
                            <a
                                href="{{ route('admin.opinion.edit', ['opinion' => $opinionResult->id]) }}"
                                class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition"
                            >
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $opinionResult->user_name ?: ('Opinion #' . $opinionResult->id) }}</p>
                                <p class="text-xs text-gray-500 truncate">ID: {{ $opinionResult->id }} • {{ $opinionResult->company ?: \Illuminate\Support\Str::limit($opinionResult->comment, 40) }}</p>
                            </a>
                        @endforeach
                    @endif

                    @if($this->faqResults->isNotEmpty())
                        <div class="px-4 pt-3 pb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t border-gray-100 dark:border-gray-700">FAQ</div>
                        @foreach($this->faqResults as $faqResult)
                            <a
                                href="{{ route('admin.faq.edit', ['faq' => $faqResult->id]) }}"
                                class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition"
                            >
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $faqResult->title ?: ('FAQ #' . $faqResult->id) }}</p>
                                <p class="text-xs text-gray-500 truncate">ID: {{ $faqResult->id }}</p>
                            </a>
                        @endforeach
                    @endif

                    @if($this->hasSearchResults === false)
                        <div class="px-4 py-3 text-sm text-gray-500">
                            Ergebnis nicht gefunden.
                        </div>
                    @endif
                @else
                    <div class="px-4 py-3 text-sm text-gray-500">
                        Select a tab and type at least 2 characters.
                    </div>
                @endif
                </div>
            </div>
        </div>
        <div class="ms-auto flex items-center gap-x-4 lg:gap-x-6">

            {{--<x-popover>
                <x-slot:trigger class="btn-ghost">
                    <x-icon name="o-rectangle-stack"/>
                </x-slot:trigger>
                <x-slot:content class="!w-70 grid grid-cols-4 gap-4">
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                    <x-button class="bg-primary text-white w-[50px] h-[50px]">
                        <x-icon name="lucide.activity"/>
                    </x-button>
                </x-slot:content>
            </x-popover>--}}

            {{--<x-button
                    class="btn-sm btn-ghost hover-none"
                    icon="o-bell-alert"
                    :link="route('admin.notification.index')"
                    wire:click="$toggle('notifications_drawer')"
            />--}}
{{--            <x-theme-toggle/>--}}
            <x-mx.theme-toggle mode="tinymce" selector=".tox-tinymce" class="btn btn-ghost" />

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true"></div>

            <!-- Profile dropdown -->
            <x-dropdown>
                <x-slot:trigger>
                    <x-button class="btn-circle"><img class="rounded-full" src="{{ auth()->user()?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}" alt=""></x-button>
                </x-slot:trigger>
{{--                <x-menu-item :title="trans('_menu.setting')" :link="route('admin.setting')"/>--}}
                <x-menu-item title="Logout" :link="route('admin.auth.logout')"/>
            </x-dropdown>
        </div>
    </div>

    <x-drawer
            wire:model="notifications_drawer"
            :title="trans('notification.models')"
            separator
            with-close-button
            close-on-escape
            class="w-11/12 lg:w-1/3"
            right
    >
        @forelse($notifications as $notif)
            <x-list-item :item="$notif">
                <x-slot:value>
                    {{App\Helpers\NotifyHelper::title($notif->data)}}
                </x-slot:value>
                <x-slot:sub-value>
                    {{\Illuminate\Support\Str::limit(App\Helpers\NotifyHelper::subTitle($notif->data))}}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-eye" class="btn-sm" wire:click="toastNotification('{{$notif->id}}')"/>
                </x-slot:actions>

            </x-list-item>
            @if($loop->last)
                <div class="flex gap-4 mt-5">
                    <x-button class="btn-primary flex-1" spinner :label="trans('notification.read_all')"/>
                    <x-button class="btn-primary flex-1" spinner :label="trans('notification.read_all')"/>
                </div>
            @endif

        @empty
            باید اینجا یک ویو لود کنیم
        @endforelse
    </x-drawer>
</div>


