<div class="card step-card">
    <div class="step-header">
        <h4 class="step-title">
            <i class="fas fa-map-marker-alt me-2"></i>
            Select Delivery Address
        </h4>
        <p class="text-muted mb-0">Choose where you want your device to be delivered</p>
    </div>
    <div class="step-body">
        @if(empty($userAddresses))
            <!-- No Addresses Available -->
            <div class="text-center py-4">
                <i class="fas fa-map-marker-alt text-muted fa-3x mb-3"></i>
                <h5>No Addresses Found</h5>
                <p class="text-muted">You need to add an address before proceeding with the order.</p>
                <a href="{{ route('user.address.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Address
                </a>
            </div>
        @else
            <!-- Address List -->
            <div class="row">
                @foreach($userAddresses as $address)
                    <div class="col-md-6 mb-3">
                        <div class="address-card {{ $selectedAddress && $selectedAddress->id == $address['id'] ? 'selected' : '' }}"
                             wire:click="selectAddress({{ $address['id'] }})"
                             style="cursor: pointer;">
                            <div class="address-card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="address-title mb-0">{{ $address['title'] }}</h6>
                                    @if($address['is_default'])
                                        <span class="badge bg-primary">Default</span>
                                    @endif
                                </div>
                                <div class="address-content">
                                    <p class="address-text mb-2">{{ $address['address'] }}</p>
                                </div>
                                
                                <!-- Selection Indicator -->
                                @if($selectedAddress && $selectedAddress->id == $address['id'])
                                    <div class="selected-indicator">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Add New Address Option -->
            <div class="row">
                <div class="col-12">
                    <div class="add-address-card" style="cursor: pointer;" onclick="window.location.href='{{ route('user.address.create') }}'">
                        <div class="add-address-body text-center">
                            <i class="fas fa-plus text-primary fa-2x mb-2"></i>
                            <h6 class="text-primary mb-0">Add New Address</h6>
                            <small class="text-muted">Create a new delivery address</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue Button -->
            @if($selectedAddress)
                <div class="step-actions mt-4">
                    <button wire:click="continueToPayment" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>
                        Continue to Payment
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>

@push('styles')
<style>
.address-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.address-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
    transform: translateY(-2px);
}

.address-card.selected {
    border-color: #28a745;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
}

.address-card-body {
    padding: 1.5rem;
}

.address-title {
    color: #333;
    font-weight: 600;
}

.address-text {
    color: #666;
    line-height: 1.5;
}

.address-details {
    font-size: 0.85rem;
}

.selected-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.2rem;
}

.add-address-card {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.add-address-card:hover {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
}

.add-address-body {
    padding: 2rem;
}

.step-actions {
    border-top: 1px solid #e9ecef;
    padding-top: 1.5rem;
    text-align: right;
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
    border-radius: 8px;
}
</style>
@endpush
