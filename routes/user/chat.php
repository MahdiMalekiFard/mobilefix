<?php

declare(strict_types=1);

use App\Livewire\User\Pages\Chat\UserChatPage;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
    Route::get('/', UserChatPage::class)->name('index');
});


