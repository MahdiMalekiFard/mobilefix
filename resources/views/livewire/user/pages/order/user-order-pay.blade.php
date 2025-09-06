<div class="container mx-auto px-4 py-8">
    <!-- Error Messages -->
    @if($errorMessage)
        <div class="bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-400 dark:text-red-300 mr-3"></i>
                <div>
                    <strong class="text-red-800 dark:text-red-200">Fehler:</strong> <span class="text-red-700 dark:text-red-300">{{ $errorMessage }}</span>
                    @if($paymentStatus === 'failed')
                        <div class="mt-2">
                            <button wire:click="retryPayment" class="inline-flex items-center gap-2 px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-md text-blue-700 dark:text-blue-300 bg-white dark:bg-blue-900/50 hover:bg-blue-50 dark:hover:bg-blue-900/70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors duration-200">
                                <i class="fas fa-redo"></i> Zahlung wiederholen
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
                1 => 'Adresse wählen',
                2 => 'Zahlungsart wählen',
                3 => 'Bezahlen'
            ]"
        />

        @if($paymentStatus === 'completed')

            <!-- Success State -->
            <div class="bg-white dark:bg-slate-900 border border-green-200 dark:border-green-800 rounded-lg shadow-lg">
                <div class="p-8 text-center">
                    <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-6xl mb-4"></i>
                    <h3 class="text-green-700 dark:text-green-300 text-2xl font-bold mb-2">Zahlung erfolgreich!</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Ihre Zahlung wurde erfolgreich verarbeitet.</p>
                    <a href="{{ route('user.order.show', $order->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 dark:bg-green-700 text-white rounded-lg font-semibold hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-green-400 transition-colors duration-200">
                        <i class="fas fa-eye"></i> Bestelldetails anzeigen
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
        <div class="bg-white dark:bg-slate-900 border border-red-200 dark:border-red-800 rounded-lg shadow-lg">
            <div class="p-8 text-center">
                <i class="fas fa-exclamation-triangle text-red-500 dark:text-red-400 text-5xl mb-4"></i>
                <h4 class="text-red-700 dark:text-red-300 text-xl font-bold mb-2">Bestellung nicht gefunden</h4>
                <p class="text-gray-600 dark:text-gray-300 mb-6">Die gesuchte Bestellung existiert nicht oder Sie haben keine Berechtigung, sie anzuzeigen.</p>
                <a href="{{ route('user.order.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i> Zurück zu Bestellungen
                </a>
            </div>
        </div>
    @endif
</div>