@props([
    'currentStep' => 1,
    'steps' => [],
    'isStepCompleted' => null,
    'isStepAccessible' => null,
    'stepTitles' => []
])

<div class="flex items-center justify-center space-x-2 py-2 mb-4">
    @foreach($steps as $stepNumber => $stepKey)
        @php
            $isCompleted = $isStepCompleted ? $isStepCompleted($stepNumber) : false;
            $isAccessible = $isStepAccessible ? $isStepAccessible($stepNumber) : true;
            $isCurrent = $currentStep == $stepNumber;
            $stepTitle = $stepTitles[$stepNumber] ?? "Step {$stepNumber}";
        @endphp
        
        <div class="flex items-center">
            <!-- Step Circle -->
            <div class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-medium transition-all duration-200
                        @if($isCompleted)
                            bg-green-500 text-white
                        @elseif($isCurrent)
                            bg-purple-500 text-white ring-2 ring-purple-300
                        @else
                            bg-gray-200 text-gray-600
                        @endif
                        @if($isAccessible && !$isCurrent) cursor-pointer hover:bg-gray-300 @endif"
                 @if($isAccessible && !$isCurrent) 
                    wire:click="goToStep({{ $stepNumber }})"
                 @endif>
                @if($isCompleted)
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @else
                    {{ $stepNumber }}
                @endif
            </div>
            
            <!-- Connector Line (not for last step) -->
            @if(!$loop->last)
                <div class="w-8 h-0.5 mx-1 transition-colors duration-200
                            @if($isCompleted) bg-green-500 @else bg-gray-200 @endif"></div>
            @endif
        </div>
    @endforeach
</div>
