<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinktrOneController;

Route::middleware(['auth', 'blocked', 'impersonate'])->group(function () {
    Route::get('/studio/linktr-one', [LinktrOneController::class, 'settings'])->name('linktr.one.settings');
    Route::post('/studio/linktr-one/style', [LinktrOneController::class, 'updateStyle'])->name('linktr.one.style.update');
    Route::post('/studio/linktr-one/category', [LinktrOneController::class, 'storeCategory'])->name('linktr.one.category.store');
    Route::post('/studio/linktr-one/category/remove/{id}', [LinktrOneController::class, 'deleteCategory'])->name('linktr.one.category.remove');
    Route::post('/studio/linktr-one/share-card', [LinktrOneController::class, 'updateShareCard'])->name('linktr.one.share-card.update');
    Route::post('/studio/linktr-one/link/{id}', [LinktrOneController::class, 'updateLinkEnhancements'])->name('linktr.one.link.update');
});
