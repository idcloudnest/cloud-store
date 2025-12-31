<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');


Route::controller(\App\Http\Controllers\Api\DigiflazzController::class)
->prefix('digiflazz')->group(function () {
	Route::get('test', 'test');
	Route::get('saldo', 'saldo');
	Route::get('produk', 'produk');
	Route::get('transaksi', 'transaksi');
	Route::get('sync', 'sync');
});

Route::prefix('webhook')
->group(function () {
	Route::controller(\App\Http\Controllers\Api\Webhook\WebhookDigiflazzController::class)
	->prefix('digiflazz')
	->group(function () {
		Route::post('callback', 'handle')->middleware('digiflazz.auth');
	});

	Route::controller(\App\Http\Controllers\Api\Webhook\WebhookMidtransController::class)
	->prefix('midtrans')
	->group(function () {
		Route::post('callback', 'handle')->middleware('digiflazz.auth');
	});
});
