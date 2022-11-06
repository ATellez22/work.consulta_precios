<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\SiteController;

Route::controller(QueryController::class)->group( function() {   
    Route::get('/', 'index')->name('query.index');
    Route::post('/query', 'show')->name('query.show');   
    Route::post('/print', 'print')->name('query.print');
});

Route::get('/barcode', [SiteController::class, 'barcode']);