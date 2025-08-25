<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    <!-- Order Header Information -->
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Order Details</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-300">#{{ $model->order_number }}</p>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-0">
                        @if($model->status)
                            @php
                                $statusEnum = \App\Enums\OrderStatusEnum::from($model->status);
                                $colorClass = match ($statusEnum->color()) {
                                    'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'info' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'danger' => 'bg-red-100 text-red-800 border-red-300',
                                    'success' => 'bg-green-100 text-green-800 border-green-300',
                                    default => 'bg-gray-100 text-gray-800 border-gray-300'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $colorClass }}">
                                {{ $statusEnum->title() }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Order Number</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $model->order_number }}</dd>
                            </div>
                        </div>
                    </div>

                    @if($model->tracking_code)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tracking Code</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $model->tracking_code }}</dd>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Amount</dt>
                                <dd class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format((int)$model->total, 0) }} Toman</dd>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Created</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $model->created_at->format('M j, Y') }}</dd>
                                <dd class="text-xs text-gray-500 dark:text-gray-400">{{ $model->created_at->format('H:i') }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer and Device Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Customer Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Customer Information</h3>
                </div>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div class="flex items-center">
                        <dt class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 w-24">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Name
                        </dt>
                        <dd class="ml-4 text-sm text-gray-900 dark:text-white font-medium">{{ $model->user_name }}</dd>
                    </div>
                    @if($model->user_phone)
                    <div class="flex items-center">
                        <dt class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 w-24">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Phone
                        </dt>
                        <dd class="ml-4 text-sm text-gray-900 dark:text-white font-medium">{{ $model->user_phone }}</dd>
                    </div>
                    @endif
                    @if($model->user_email)
                    <div class="flex items-center">
                        <dt class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 w-24">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email
                        </dt>
                        <dd class="ml-4 text-sm text-gray-900 dark:text-white font-medium">{{ $model->user_email }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Device Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Device Information</h3>
                </div>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    @if($model->brand)
                    <div class="flex items-center">
                        <dt class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 w-20">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v16l-8 2-8-2V1a1 1 0 011-1h2a1 1 0 011 1v3z"></path>
                            </svg>
                            Brand
                        </dt>
                        <dd class="ml-4 text-sm text-gray-900 dark:text-white font-medium">{{ $model->brand->title }}</dd>
                    </div>
                    @endif
                    @if($model->device)
                    <div class="flex items-center">
                        <dt class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 w-20">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Model
                        </dt>
                        <dd class="ml-4 text-sm text-gray-900 dark:text-white font-medium">{{ $model->device->title }}</dd>
                    </div>
                    @endif
                    @if($model->paymentMethod)
                    <div class="flex items-center">
                        <dt class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 w-20">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Payment
                        </dt>
                        <dd class="ml-4 text-sm text-gray-900 dark:text-white font-medium">{{ $model->paymentMethod->title }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <!-- Address Information -->
    @if($model->address)
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Address Information</h3>
                </div>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address Title</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $model->address->title }}</dd>
                    </div>
                    @if($model->address->address)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $model->address->address }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    @endif

    <!-- Services & Repairs -->
    @if($model->problems && $model->problems->count() > 0)
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Services & Repairs</h3>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">({{ $model->problems->count() }} services)</span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($model->problems as $problem)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 bg-orange-500 dark:bg-orange-600 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $problem->title }}</h4>
                                    @if($problem->description)
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">{{ $problem->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                @if($problem->min_price || $problem->max_price)
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if($problem->min_price && $problem->max_price)
                                        @if($problem->min_price == $problem->max_price)
                                            {{ number_format((int)$problem->min_price, 0) }} Toman
                                        @else
                                            {{ number_format((int)$problem->min_price, 0) }} - {{ number_format((int)$problem->max_price, 0) }} Toman
                                        @endif
                                    @elseif($problem->min_price)
                                        From {{ number_format((int)$problem->min_price, 0) }} Toman
                                    @elseif($problem->max_price)
                                        Up to {{ number_format((int)$problem->max_price, 0) }} Toman
                                    @endif
                                </div>
                                @else
                                <span class="text-xs text-gray-500 dark:text-gray-400">Price on request</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Summary -->
                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Services:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $model->problems->count() }} items</span>
                    </div>
                    @php
                        $totalMinPrice = $model->problems->whereNotNull('min_price')->sum('min_price');
                        $totalMaxPrice = $model->problems->whereNotNull('max_price')->sum('max_price');
                    @endphp
                    @if($totalMinPrice > 0 || $totalMaxPrice > 0)
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Price Range:</span>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                            @if($totalMinPrice > 0 && $totalMaxPrice > 0)
                                {{ number_format((int)$totalMinPrice, 0) }} - {{ number_format((int)$totalMaxPrice, 0) }} Toman
                            @elseif($totalMinPrice > 0)
                                From {{ number_format((int)$totalMinPrice, 0) }} Toman
                            @elseif($totalMaxPrice > 0)
                                Up to {{ number_format((int)$totalMaxPrice, 0) }} Toman
                            @endif
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Customer Note -->
    @if($model->user_note)
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Customer Note</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-700">
                    <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed">{{ $model->user_note }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Admin Note -->
    @if($model->admin_note)
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Admin Note</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-700">
                    <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed">{{ $model->admin_note }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Media/Attachments -->
    @if($model->media && $model->media->count() > 0)
        @php
            // Get videos once, so we can (1) read their poster_url and (2) hide poster images
            $videos     = $model->getMedia('videos');
            $posterIds  = $videos->map(fn($v) => $v->getCustomProperty('poster_media_id'))->filter()->values()->all();
        @endphp

        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Attachments</h3>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ $model->media->count() }} files)</span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($model->media as $media)
                            @php $mime = $media->mime_type ?? ''; @endphp

                            {{-- Skip poster images so they don’t show as normal images --}}
                            @if(in_array($media->id, $posterIds, true))
                                @continue
                            @endif

                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 overflow-hidden hover:shadow-md transition-shadow duration-200">
                                <div class="relative bg-gray-50 dark:bg-gray-600">

                                    {{-- IMAGE PREVIEW --}}
                                    @if(str_starts_with($mime, 'image/'))
                                        <div class="w-full h-32 bg-gray-100 dark:bg-gray-600">
                                            <img
                                                src="{{ $media->getUrl() }}"
                                                alt="Image"
                                                class="w-full h-32 object-cover block rounded-none"
                                            >
                                            <div class="fallback-image w-full h-32 bg-gray-100 dark:bg-gray-600 items-center justify-center hidden">
                                                <div class="text-center">
                                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">Image Preview</span>
                                                </div>
                                            </div>
                                        </div>

                                    {{-- VIDEO PREVIEW (use poster) --}}
                                    @elseif(str_starts_with($mime, 'video/'))
                                        @php
                                            $poster = $media->getCustomProperty('poster_url') ?: asset('assets/images/default/video_poster.jpg');
                                        @endphp

                                        <a href="{{ $media->getUrl() }}" target="_blank" class="block">
                                            <div class="relative w-full h-32 bg-black overflow-hidden">
                                                <img src="{{ $poster }}" alt="Video Poster" class="absolute inset-0 w-full h-full object-cover">
                                                <span class="absolute inset-0 flex items-center justify-center">
                                                  <i class="fa-solid fa-play text-white text-lg bg-black/60 rounded-full p-2"></i>
                                                </span>
                                            </div>
                                        </a>
                                    @else
                                        <div class="w-full h-32 bg-gray-100 dark:bg-gray-600 flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Document</span>
                                            </div>
                                        </div>
                                        <!-- File type badge -->
                                        <div class="absolute top-2 right-2">
                                            <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full font-medium">FILE</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- File Information -->
                                <div class="p-3 border-t border-gray-100 dark:border-gray-600">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $media->name }}">
                                                {{ $media->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ strtoupper(pathinfo($media->name, PATHINFO_EXTENSION) ?: 'FILE') }}
                                                @if(isset($media->size))
                                                    • {{ number_format($media->size / 1024, 1) }} KB
                                                @endif
                                            </p>
                                        </div>
                                        @if(str_starts_with($mime, 'image/'))
                                            <div class="ml-3">
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="inline-flex items-center px-2 py-1 border border-gray-300 dark:border-gray-500 rounded-md text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                                    <svg class="w-3 h-3 mr-1 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                    Open
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
