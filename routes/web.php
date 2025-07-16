<?php

use App\Livewire\Web\Pages\HomePage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home-page');
