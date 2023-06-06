<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// +-+-+-+-+-+-+-+- TOP +-+-+-+-+-+-+-+-
use App\Http\Controllers\Top\TopController;

Route::get('/', function () {
    return view('welcome');
});

// ログインとステータスチェック
Route::middleware(['auth'])->group(function () {
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ Top ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
        // -+-+-+-+-+-+-+-+-+-+-+-+ TOP -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(TopController::class)->prefix('top')->name('top.')->group(function(){
            Route::get('', 'index')->name('index');
        });


});


require __DIR__.'/auth.php';
