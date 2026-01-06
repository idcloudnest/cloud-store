<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::as('api.')->group(function () {
	Route::controller(App\Http\Controllers\Api\DigiflazzController::class)
	->as('digiflazz.')->prefix('digiflazz')->group(function () {
		Route::get('test', 'test');
		Route::get('saldo', 'saldo');
		Route::get('produk', 'produk');
		Route::get('transaksi', 'transaksi');
		Route::post('check-user', 'checkUser')->name('check-user');
	});

	Route::controller(App\Http\Controllers\Api\ProviderController::class)
	->as('provider.')->prefix('provider')->group(function () {
		Route::post('sync-product', 'syncProduct')->name('sync-product');
		Route::post('check-username', 'checkUsername')->name('check-username');
	});

	Route::prefix('webhook')
	->group(function () {
		Route::controller(\App\Http\Controllers\Api\Webhook\WebhookDigiflazzController::class)
		->prefix('digiflazz')
		->group(function () {
			Route::post('callback', 'handle')->middleware('digiflazz.auth');
		});

		Route::controller(\App\Http\Controllers\Api\Webhook\WebhookVipaymentController::class)
		->prefix('vipayment')
		->group(function () {
			Route::get('callback', 'handle');
		});

		Route::controller(\App\Http\Controllers\Api\Webhook\WebhookMidtransController::class)
		->prefix('midtrans')
		->group(function () {
			Route::post('callback', 'handle');
		});
	});
});
