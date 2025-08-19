@php
    use Illuminate\Support\Arr;
    $classes = trim('join-item btn btn-sm btn-outline btn-primary');
@endphp

<div class="md:flex md:items-center md:justify-between py-3">
    <div class="min-w-0 flex-1">
        <x-breadcrumbs :items="$breadcrumbs ?? $this->breadcrumbs"/>
    </div>

    <div class="mt-5 flex lg:mt-0 lg:ms-4 join">
        @foreach(array_reverse($breadcrumbsActions ?? ($this->breadcrumbsActions ?? [])) as $action)
            @if(Arr::get($action,'access', true))
                @php $navigate = Arr::get($action, 'navigate', true); @endphp

                @if($navigate)
                    {{-- SPA (default) --}}
                    <x-button
                        :label="Arr::get($action,'label')"
                        :icon="Arr::get($action,'icon')"
                        :link="Arr::get($action,'link')"
                        @class(['join-item btn-sm btn-outline btn-primary', Arr::get($action,'class')])
                        wire:navigate
                    />
                @else
                    {{-- Hard reload (no SPA) — SAME classes --}}
                    <a href="{{ Arr::get($action,'link') }}" class="{{ $classes }}">
                        @if(Arr::get($action,'icon'))
                            <x-icon :name="Arr::get($action,'icon')" class="w-4 h-4 me-2" />
                        @endif
                        {{ Arr::get($action,'label') }}
                    </a>
                @endif
            @endif
        @endforeach
    </div>
</div>
