<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return redirect()->route('category.index');
});

Route::resource('category', CategoryController::class);

