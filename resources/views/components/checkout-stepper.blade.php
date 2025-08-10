@props([
    'currentStep' => 1,
    'steps' => [],
    'isStepCompleted' => null,
    'isStepAccessible' => null,
    'stepTitles' => []
])

<div class="checkout-stepper py-4">
    <div class="stepper-container">
        <div class="stepper-wrapper">
            @foreach($steps as $stepNumber => $stepKey)
                @php
                    $isCompleted = $isStepCompleted ? $isStepCompleted($stepNumber) : false;
                    $isAccessible = $isStepAccessible ? $isStepAccessible($stepNumber) : true;
                    $isCurrent = $currentStep == $stepNumber;
                    $stepTitle = $stepTitles[$stepNumber] ?? "Step {$stepNumber}";
                @endphp
                
                <div class="stepper-item {{ $isCurrent ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }} {{ $isAccessible ? 'accessible' : 'disabled' }}">
                    <!-- Step Circle -->
                    <div class="stepper-circle" 
                         @if($isAccessible && !$isCurrent) 
                            wire:click="goToStep({{ $stepNumber }})" 
                            style="cursor: pointer;"
                         @endif>
                        @if($isCompleted)
                            <i class="fas fa-check"></i>
                        @else
                            <span class="step-number">{{ $stepNumber }}</span>
                        @endif
                    </div>
                    
                    <!-- Step Title -->
                    <div class="stepper-label">
                        <span class="step-title">{{ $stepTitle }}</span>
                    </div>
                    
                    <!-- Connector Line (not for last step) -->
                    @if(!$loop->last)
                        <div class="stepper-line {{ $isCompleted ? 'completed' : '' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('styles')
<style>
.checkout-stepper {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.stepper-container {
    padding: 2rem;
}

.stepper-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.stepper-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
    z-index: 2;
}

.stepper-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s ease;
    position: relative;
    z-index: 3;
    border: 3px solid transparent;
}

.stepper-item .stepper-circle {
    background: rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.6);
    border-color: rgba(255, 255, 255, 0.3);
}

.stepper-item.accessible .stepper-circle {
    background: rgba(255, 255, 255, 0.3);
    color: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.5);
}

.stepper-item.active .stepper-circle {
    background: #fff;
    color: #667eea;
    border-color: #fff;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.stepper-item.completed .stepper-circle {
    background: #28a745;
    color: #fff;
    border-color: #28a745;
}

.stepper-item.disabled .stepper-circle {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.2);
    cursor: not-allowed;
}

.stepper-label {
    margin-top: 1rem;
    text-align: center;
}

.step-title {
    color: #fff;
    font-size: 0.9rem;
    font-weight: 600;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.stepper-item.active .step-title,
.stepper-item.completed .step-title {
    opacity: 1;
}

.stepper-item.disabled .step-title {
    opacity: 0.4;
}

.stepper-line {
    position: absolute;
    top: 25px;
    left: calc(50% + 25px);
    right: calc(-50% + 25px);
    height: 3px;
    background: rgba(255, 255, 255, 0.2);
    z-index: 1;
    transition: background 0.3s ease;
}

.stepper-line.completed {
    background: #28a745;
}

.step-number {
    font-size: 1.1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stepper-container {
        padding: 1rem;
    }
    
    .stepper-circle {
        width: 40px;
        height: 40px;
    }
    
    .step-title {
        font-size: 0.8rem;
    }
    
    .stepper-line {
        top: 20px;
        left: calc(50% + 20px);
        right: calc(-50% + 20px);
    }
}

@media (max-width: 576px) {
    .stepper-wrapper {
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
    }
    
    .stepper-item {
        flex-direction: row;
        width: 100%;
        max-width: 300px;
    }
    
    .stepper-label {
        margin-top: 0;
        margin-left: 1rem;
        text-align: left;
    }
    
    .stepper-line {
        display: none;
    }
}
</style>
@endpush
