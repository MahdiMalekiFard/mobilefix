<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Team\TeamUpdateOrCreate;
use App\Livewire\Admin\Pages\Team\TeamTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/team', 'as' => 'admin.team.'], function () {
    Route::get('/', TeamTable::class)->name('index');
    Route::get('create', TeamUpdateOrCreate::class)->name('create')->can('create,App\Models\Team');
    Route::get('{team}/edit', TeamUpdateOrCreate::class)->name('edit')->can('update,team');
});
