<?php

use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name("home");
Route::post('/documents', [DocumentsController::class, "store"])->name("files.upload");
Route::get('/documents', [DocumentsController::class, "list"])->name("files.list");
Route::delete('/documents/{document}', [DocumentsController::class, "destroy"])->name("files.destroy");
Route::get('/messages', [MessagesController::class, "index"])->name("messages");
