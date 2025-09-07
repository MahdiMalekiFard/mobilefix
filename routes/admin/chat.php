<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Chat\AdminChatList;
use App\Livewire\Admin\Pages\Chat\AdminChatShow;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/chat', 'as' => 'admin.chat.'], function () {
    Route::get('/', AdminChatList::class)->name('index');
    Route::get('/{conversation}', AdminChatShow::class)->name('show');
});


