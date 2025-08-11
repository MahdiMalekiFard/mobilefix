<div class="container mx-auto px-4 py-8">
    <!-- Error Messages -->
    @if($errorMessage)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                <div>
                    <strong class="text-red-800">Error:</strong> <span class="text-red-700">{{ $errorMessage }}</span>
                    @if($paymentStatus === 'failed')
                        <div class="mt-2">
                            <button wire:click="retryPayment" class="inline-flex items-center gap-2 px-3 py-2 border border-blue-300 rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <i class="fas fa-redo"></i> Retry Payment
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($order)

        <!-- Checkout Stepper -->
        <x-checkout-stepper 
            :currentStep="$currentStep"
            :steps="$steps"
            :isStepCompleted="fn($step) => $this->isStepCompleted($step)"
            :isStepAccessible="fn($step) => $this->isStepAccessible($step)"
            :stepTitles="[
                1 => 'Select Address',
                2 => 'Choose Payment',
                3 => 'Pay'
            ]"
        />

        @if($paymentStatus === 'completed')

            <!-- Success State -->
            <div class="bg-white border border-green-200 rounded-lg shadow-lg">
                <div class="p-8 text-center">
                    <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                    <h3 class="text-green-700 text-2xl font-bold mb-2">Payment Successful!</h3>
                    <p class="text-gray-600 mb-6">Your payment has been processed successfully.</p>
                    <a href="{{ route('user.order.show', $order->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-eye"></i> View Order Details
                    </a>
                </div>
            </div>

        @else

            <!-- Step Content -->
            @if($currentStep === 1)
                <!-- Step 1: Address Selection -->
                @include('livewire.user.pages.order.steps.address-selection')
            @elseif($currentStep === 2)
                <!-- Step 2: Payment Method Selection -->
                @include('livewire.user.pages.order.steps.payment-selection')
            @elseif($currentStep === 3)
                <!-- Step 3: Review and Pay -->
                @include('livewire.user.pages.order.steps.review-and-pay')
            @endif

        @endif

    @else
        <!-- No Order State -->
        <div class="bg-white border border-red-200 rounded-lg shadow-lg">
            <div class="p-8 text-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-5xl mb-4"></i>
                <h4 class="text-red-700 text-xl font-bold mb-2">Order Not Found</h4>
                <p class="text-gray-600 mb-6">The order you're looking for doesn't exist or you don't have permission to view it.</p>
                <a href="{{ route('user.order.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    @endif

    <!-- Address Modal Component -->
    @livewire('user.shared.user-address-modal')
</div>