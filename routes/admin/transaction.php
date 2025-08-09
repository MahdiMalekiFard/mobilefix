<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Transaction\TransactionUpdateOrCreate;
use App\Livewire\Admin\Pages\Transaction\TransactionTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/transaction', 'as' => 'admin.transaction.'], function () {
    Route::get('/', TransactionTable::class)->name('index');
    Route::get('create', TransactionUpdateOrCreate::class)->name('create')->can('create,App\Models\Transaction');
    Route::get('{transaction}/edit', TransactionUpdateOrCreate::class)->name('edit')->can('update,transaction');
});
