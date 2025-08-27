<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Livewire\Concerns\LocksTranslatorToFallback;

abstract class BaseAdminComponent extends Component
{
    use LocksTranslatorToFallback;
}
