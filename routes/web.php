<?php
use Illuminate\Support\Facades\Log;

Route::get('/', 'GuestController@index')->name('index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/shopify', 'Auth\LoginShopifyController@redirectToProvider')->name('login.shopify');
Route::get('login/shopify/callback', 'Auth\LoginShopifyController@handleProviderCallback');

Route::post('webhook/shopify/order-created', function(\Illuminate\Http\Request $request) {
    Log::debug('Received a webhook from shopify.');
})->middleware('webhook');