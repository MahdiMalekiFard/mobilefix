<div class="py-6">
    <!-- Password Setup Component -->
    <livewire:user.shared.password-setup-component />

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <x-card shadow>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Gesamtbestellungen</div>
                    <div class="text-2xl font-semibold">{{$this->stats['total']}}</div>
                </div>
                <x-icon name="o-rectangle-stack" class="text-primary" />
            </div>
        </x-card>
        <x-card shadow>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Unter Prüfung</div>
                    <div class="text-2xl font-semibold">{{$this->stats['pending']}}</div>
                </div>
                <x-icon name="o-clock" class="text-warning" />
            </div>
        </x-card>
        <x-card shadow>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">In Reparatur</div>
                    <div class="text-2xl font-semibold">{{$this->stats['processing']}}</div>
                </div>
                <x-icon name="o-cog-8-tooth" class="text-info" />
            </div>
        </x-card>
        <x-card shadow>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Zahlung steht aus</div>
                    <div class="text-2xl font-semibold">{{$this->stats['completed']}}</div>
                </div>
                <x-icon name="o-check-badge" class="text-success" />
            </div>
        </x-card>
        <x-card shadow>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Bezahlt</div>
                    <div class="text-2xl font-semibold">{{$this->stats['paid']}}</div>
                </div>
                <x-icon name="o-banknotes" class="text-success" />
            </div>
        </x-card>
        <x-card shadow>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Geliefert</div>
                    <div class="text-2xl font-semibold">{{$this->stats['delivered']}}</div>
                </div>
                <x-icon name="o-truck" class="text-success" />
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mt-6">
        <x-card shadow class="xl:col-span-2" title="Letzte Bestellungen">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Bestellnummer</th>
                        <th>Status</th>
                        <th>Gesamt</th>
                        <th>Aktualisiert</th>
                        <th>Aktionen</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($this->recentOrders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->order_number}}</td>
                            <td>
                                @php($statusEnum = \App\Enums\OrderStatusEnum::from($order->status))
                                @php($colorClass = match ($statusEnum->color()) {
                                    'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'info' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'danger' => 'bg-red-100 text-red-800 border-red-200',
                                    'success' => 'bg-green-100 text-green-800 border-green-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                })
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{$colorClass}}">{{$statusEnum->title()}}</span>
                            </td>
                            <td>
                                @if((float) $order->total > 0)
                                    {{ number_format((float) $order->total, 0) }}
                                @else
                                    <span class="text-red-400">nicht angegeben</span>
                                @endif
                            </td>
                            <td>{{$order->updated_at->diffForHumans()}}</td>
                            <td>
{{--                                <a class="btn btn-ghost btn-xs" href="{{ route('user.order.show', $order->id) }}">Sicht</a>--}}
                                <a
                                    href="{{ route('user.order.show', $order->id) }}"
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full border
                                         text-gray-600 border-gray-300 hover:text-gray-900 hover:bg-gray-100 hover:border-indigo-500
                                         focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500
                                         dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-white/10 dark:hover:border-indigo-400
                                         transition"
                                    aria-label="Sicht"
                                    title="Sicht"
                                >
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500">Keine aktuellen Bestellungen</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card shadow title="Ausstehende Zahlungen">
            <div class="space-y-3">
                @forelse($this->pendingPayments as $order)
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium">{{$order->order_number}}</div>
                            <div class="text-sm text-gray-500">
                                @if((float) $order->total > 0)
                                    {{ number_format((float) $order->total, 0) }}
                                @else
                                    <span class="text-red-400">nicht angegeben</span>
                                @endif
                            </div>
                        </div>
                        <a class="btn btn-primary btn-sm" href="{{ route('user.order.pay', $order->id) }}">Pay</a>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Keine ausstehenden Zahlungen</div>
                @endforelse
            </div>
            <div class="mt-4">
                <a class="btn btn-outline btn-sm" href="{{ route('user.order.index') }}">Alle Bestellungen anzeigen</a>
            </div>
        </x-card>
    </div>
</div>
