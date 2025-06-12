<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inquiry_list', [App\Http\Controllers\InquiryController::class, 'index']);

Route::get('/inquirylist', function () {
    // You can pass data here if needed
    return view('inquiry_list');
});

Route::get('/inquiry-detail/{id}', function ($id) {
    // In a real app, fetch inquiry details from DB using $id
    return view('inquiry_detail', ['id' => $id]);
});
