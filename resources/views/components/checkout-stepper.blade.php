@props([
    'currentStep' => 1,
    'steps' => [],
    'isStepCompleted' => null,
    'isStepAccessible' => null,
    'stepTitles' => []
])

<div class="relative flex items-center justify-center py-4 mb-6">
    @foreach($steps as $stepNumber => $stepKey)
        @php
            $isCompleted = $isStepCompleted ? $isStepCompleted($stepNumber) : false;
            $isAccessible = $isStepAccessible ? $isStepAccessible($stepNumber) : true;
            $isCurrent = $currentStep == $stepNumber;
            $stepTitle = $stepTitles[$stepNumber] ?? "Step {$stepNumber}";
        @endphp
        
        <div class="flex flex-col items-center relative">
            <!-- Step Circle -->
            <div class="flex items-center justify-center w-12 h-12 rounded-full text-sm font-bold transition-all duration-300 ease-in-out transform
                        @if($isCompleted)
                            bg-green-500 text-white shadow-lg scale-105 ring-4 ring-green-200
                        @elseif($isCurrent)
                            bg-gradient-to-r from-purple-500 to-blue-600 text-white shadow-xl scale-110 ring-4 ring-purple-300 animate-pulse
                        @else
                            bg-gray-200 text-gray-600 hover:bg-gray-300
                        @endif
                        @if($isAccessible && !$isCurrent) cursor-pointer hover:scale-105 hover:shadow-md @endif"
                 @if($isAccessible && !$isCurrent) 
                    wire:click="goToStep({{ $stepNumber }})"
                 @endif>
                @if($isCompleted)
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @else
                    {{ $stepNumber }}
                @endif
            </div>
            
            <!-- Step Title -->
            <div class="mt-3 text-xs font-medium text-center transition-colors duration-200
                        @if($isCurrent)
                            text-purple-600 font-semibold
                        @elseif($isCompleted)
                            text-green-600
                        @else
                            text-gray-500
                        @endif">
                {{ $stepTitle }}
            </div>
            
            <!-- Connector Line (not for last step) -->
            @if(!$loop->last)
                <div class="absolute top-6 left-full w-20 h-1 transition-all duration-300 z-0
                            @if($stepNumber < $currentStep) 
                                bg-gradient-to-r from-green-500 to-green-400 
                            @else 
                                bg-gray-200 
                            @endif"></div>
            @endif
        </div>
        
        @if(!$loop->last)
            <div class="w-20"></div>
        @endif
    @endforeach
</div>
