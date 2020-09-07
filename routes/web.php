<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('push', function () {
   return view('push-notif');
});

Route::get('test', function () {
   event(new App\Events\PushNotification('Someone'));
   return "Event has been sent!";
});

Route::get('/', 'ChatsController@index');
Route::get('messages', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');

Route::get('/home', 'HomeController@index')->name('home');
