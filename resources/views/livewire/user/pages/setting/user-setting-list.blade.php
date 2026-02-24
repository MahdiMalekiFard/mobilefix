<form wire:submit="submit" class="py-6 space-y-6">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    <x-card shadow class="overflow-hidden !p-0">
        <div class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 text-white px-5 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img
                        class="w-16 h-16 rounded-full object-cover ring-2 ring-white/70"
                        src="{{ auth()->user()?->getFirstMediaUrl('avatar') ?? asset('assets/images/default/user-avatar.png') }}"
                        alt="User Avatar"
                    />
                    <div>
                        <p class="text-lg font-bold">{{ auth()->user()?->name }}</p>
                        <p class="text-sm text-white/90">{{ auth()->user()?->email ?: 'Kein E-Mail gesetzt' }}</p>
                    </div>
                </div>

                <div class="text-sm text-white/90">
                    Mitglied seit {{ auth()->user()?->created_at?->format('d.m.Y') }}
                </div>
            </div>
        </div>

        <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 bg-white dark:bg-base-100">
                <p class="text-xs text-gray-500">Bestellungen</p>
                <p class="text-xl font-semibold">{{ auth()->user()?->orders()->count() }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 bg-white dark:bg-base-100">
                <p class="text-xs text-gray-500">Adressen</p>
                <p class="text-xl font-semibold">{{ auth()->user()?->addresses()->count() }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 bg-white dark:bg-base-100">
                <p class="text-xs text-gray-500">Account Status</p>
                <p class="text-xl font-semibold text-green-600">Aktiv</p>
            </div>
        </div>
    </x-card>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <x-card title="Persönliche Informationen" shadow separator progress-indicator="submit" class="xl:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <x-input
                    label="Name"
                    wire:model="name"
                    placeholder="Ihr Name"
                    :error="$errors->first('name')"
                />

                <x-input
                    label="E-Mail"
                    wire:model="email"
                    placeholder="name@example.com"
                    :error="$errors->first('email')"
                />

                <x-input
                    label="Mobilnummer"
                    wire:model="mobile"
                    placeholder="+49..."
                    :error="$errors->first('mobile')"
                />
            </div>
        </x-card>

        <x-card title="Schnellzugriff" shadow separator>
            <div class="space-y-3">
                <a href="{{ route('user.order.index') }}" class="btn btn-outline btn-sm w-full justify-start">
                    <i class="fa-solid fa-box"></i>
                    <span>Meine Bestellungen</span>
                </a>
                <a href="{{ route('user.address.index') }}" class="btn btn-outline btn-sm w-full justify-start">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Meine Adressen</span>
                </a>
                <a href="{{ route('user.chat.index') }}" class="btn btn-outline btn-sm w-full justify-start">
                    <i class="fa-regular fa-comments"></i>
                    <span>Support Chat</span>
                </a>
            </div>
        </x-card>
    </div>

    <div class="flex justify-end">
        <x-button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="submit">
            Speichern
        </x-button>
    </div>
</form>
