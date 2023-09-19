<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MessageController;

// 獲取所有留言
Route::get('/messages', 'MessageController@index');

// 創建新留言
Route::post('/messages', 'MessageController@create');

// 刪除特定id留言
// Route::delete('/messages/{id}', 'MessageController@delete');