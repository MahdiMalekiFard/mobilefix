<?php

use App\Livewire\Admin\Pages\Chat\AdminChatShow;
use App\Livewire\Admin\Pages\Chat\AdminChatApp;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/chat', 'as' => 'admin.chat.'], function () {
    Route::get('/', AdminChatApp::class)->name('index');
    Route::get('/{conversation}', AdminChatShow::class)->name('show');
});
