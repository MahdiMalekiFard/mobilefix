<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
        <!-- Total Messages -->
        <div class="group rounded-2xl border border-zinc-200/80 bg-white/70 dark:bg-zinc-900/60 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="size-12 rounded-xl bg-blue-500/10 flex items-center justify-center">
                            <i class="fas fa-envelope text-blue-600 dark:text-blue-400 text-2xl"></i>
                        </div>
                    </div>
                    <div class="grow ms-4">
                        <p class="uppercase tracking-wide text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Messages</p>
                        <h4 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mt-0.5">
                            {{ $contactStats['total'] }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unread -->
        <div class="group rounded-2xl border border-zinc-200/80 bg-white/70 dark:bg-zinc-900/60 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow {{ $contactStats['unread'] ? 'ring-1 ring-amber-400/60' : '' }}">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="size-12 rounded-xl bg-amber-500/10 flex items-center justify-center">
                            <i class="fas fa-eye-slash text-amber-600 dark:text-amber-400 text-2xl"></i>
                        </div>
                    </div>
                    <div class="grow ms-4">
                        <p class="uppercase tracking-wide text-xs font-medium text-zinc-500 dark:text-zinc-400">Unread</p>
                        <h4 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mt-0.5">
                            {{ $contactStats['unread'] }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today -->
        <div class="group rounded-2xl border border-zinc-200/80 bg-white/70 dark:bg-zinc-900/60 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="size-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                            <i class="fas fa-calendar-day text-emerald-600 dark:text-emerald-400 text-2xl"></i>
                        </div>
                    </div>
                    <div class="grow ms-4">
                        <p class="uppercase tracking-wide text-xs font-medium text-zinc-500 dark:text-zinc-400">Today</p>
                        <h4 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mt-0.5">
                            {{ $contactStats['today'] }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($contactStats['unread'] > 0)
        <!-- Quick Actions -->
        <div class="rounded-2xl border border-zinc-200/80 bg-white/80 dark:bg-zinc-900/60 dark:border-zinc-800 shadow-sm">
            <div class="px-5 py-4 border-b border-zinc-200/70 dark:border-zinc-800">
                <h5 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">Quick Actions</h5>
            </div>
            <div class="p-5">
                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('admin.contact-us.index') }}?filters[is_read]=0"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-amber-500 text-white font-medium hover:bg-amber-600 active:translate-y-[1px] transition"
                    >
                        <i class="fas fa-eye"></i>
                        <span>View Unread Messages</span>
                    </a>

                    <a
                        href="{{ route('admin.contact-us.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 active:translate-y-[1px] transition"
                    >
                        <i class="fas fa-list"></i>
                        <span>View All Messages</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
