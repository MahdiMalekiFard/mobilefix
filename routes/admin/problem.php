<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Problem\ProblemUpdateOrCreate;
use App\Livewire\Admin\Pages\Problem\ProblemTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/problem', 'as' => 'admin.problem.'], function () {
    Route::get('/', ProblemTable::class)->name('index');
    Route::get('create', ProblemUpdateOrCreate::class)->name('create')->can('create,App\Models\Problem');
    Route::get('{problem}/edit', ProblemUpdateOrCreate::class)->name('edit')->can('update,problem');
});
