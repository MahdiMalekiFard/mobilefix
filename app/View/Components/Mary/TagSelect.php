<?php

declare(strict_types=1);

namespace App\View\Components\Mary;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Tags\Tag;

class TagSelect extends Component
{
    public string $uuid;
    public array $options;
    public ?array $selected;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?string $icon = 'o-tag',
        public ?string $iconRight = null,
        public ?bool $inline = false,
        public ?bool $clearable = true,
        public ?string $prefix = null,
        public ?string $suffix = null,

        // Slots
        public mixed $prepend = null,
        public mixed $append = null,

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,

        // Optional: override options from outside
        ?array $selected = null,
        ?array $options = null,
    ) {
        $this->uuid     = 'mary' . md5(serialize($this)) . $id;
        $this->options  = $options ?? Tag::pluck('name')->toArray();
        $this->selected = $selected;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function isReadonly(): bool
    {
        return $this->attributes->get('readonly') === true;
    }

    public function isDisabled(): bool
    {
        return $this->attributes->get('disabled') === true;
    }

    public function isRequired(): bool
    {
        return $this->attributes->get('required') === true;
    }

    public function render(): View|Closure|string
    {
        return view('components.mary.tag-select');
    }
}
