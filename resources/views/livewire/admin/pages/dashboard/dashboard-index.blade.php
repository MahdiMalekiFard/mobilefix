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

    <!-- Latest User Orders -->
    <div class="rounded-2xl border border-zinc-200/80 bg-white/80 dark:bg-zinc-900/60 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-200/70 dark:border-zinc-800 flex items-center justify-between gap-3">
            <h5 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">Latest User Orders</h5>
            <a href="{{ route('admin.order.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                View all
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                <tr class="text-zinc-500 dark:text-zinc-400">
                    <th class="px-5 py-3 text-left font-medium">Order</th>
                    <th class="px-5 py-3 text-left font-medium">User</th>
                    <th class="px-5 py-3 text-left font-medium">Status</th>
                    <th class="px-5 py-3 text-left font-medium">Total</th>
                    <th class="px-5 py-3 text-left font-medium">Updated</th>
                    <th class="px-5 py-3 text-left font-medium">Action</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800">
                @forelse($latestOrders as $order)
                    @php($statusEnum = \App\Enums\OrderStatusEnum::from($order->status))
                    @php($colorClass = match ($statusEnum->color()) {
                        'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'info' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'danger' => 'bg-red-100 text-red-800 border-red-200',
                        'success' => 'bg-green-100 text-green-800 border-green-200',
                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                    })
                    <tr class="text-zinc-800 dark:text-zinc-200">
                        <td class="px-5 py-3 font-medium whitespace-nowrap">#{{ $order->order_number }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $order->user?->name ?? 'Unknown user' }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $colorClass }}">
                                {{ $statusEnum->title() }}
                            </span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ number_format((float) $order->total, 0) }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $order->updated_at?->diffForHumans() }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <a
                                href="{{ route('admin.order.show', ['order' => $order->id]) }}"
                                class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                <i class="fas fa-eye text-xs"></i>
                                <span>Show</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-6 text-center text-zinc-500 dark:text-zinc-400">
                            No orders yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
