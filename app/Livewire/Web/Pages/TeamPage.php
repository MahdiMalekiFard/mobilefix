<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Enums\YesNoEnum;
use App\Models\Team;
use Livewire\Component;
use Livewire\WithPagination;

class TeamPage extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $teams = Team::query()->where('special', YesNoEnum::YES)->paginate(8);

        return view('livewire.web.pages.team-page', [
            'teams' => $teams,
        ])
            ->layout('components.layouts.web');
    }
}
