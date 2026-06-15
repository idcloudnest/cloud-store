<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

Route::as('pages.')
->group(function () {
	Route::controller(\App\Http\Controllers\Pages\HomeController::class)
	->group(function () {
		Route::get('/', 'home')->name('home');
		Route::get('terms-condition', 'terms')->name('terms');
		Route::get('privacy-policy', 'privacyPolicy')->name('privacyPolicy');
		Route::view('cara-order', 'pages.cara-order')->name('cara-order');
		Route::view('hubungi-kami', 'pages.contact')->name('contact');
	});

	Route::controller(\App\Http\Controllers\TransactionController::class)
	->group(function () {
		Route::get('invoices', 'invoices')->name('invoices');
	});
});

Route::controller(App\Http\Controllers\Auth\ResetPasswordController::class)
->group(function () {
	Route::get('password/reset/{token}', 'showResetForm')->name('password.reset'); // Nama ini WAJIB 'password.reset'
	Route::post('password/reset', 'reset')->name('password.update');
});
Route::prefix('auth')->as('auth.')
->group(function () {
	Route::controller(App\Http\Controllers\Auth\FirebaseAuthController::class)
	->prefix('firebase')
	->as('firebase.')
	->group(function () {
		Route::post('firebase-login', 'login')->name('login');
	});

	Route::controller(\App\Http\Controllers\Auth\AuthController::class)
	->middleware('guest')
	->group(function () {
		Route::get('login', 'showLoginForm')->name('login');
		Route::post('login', 'login')->name('login.process');
		Route::get('register', 'showRegisterForm')->name('register');
		Route::post('register', 'register')->name('register.process');
	});

	Route::controller(\App\Http\Controllers\Auth\AuthController::class)
	->middleware('auth')
	->group(function () {
		Route::get('logout', 'logout')->name('logout');
	});

	Route::controller(\App\Http\Controllers\Auth\ForgotPasswordController::class)
	->group(function () {
		// Menampilkan Form
		Route::get('forgot-password', 'showLinkRequestForm')->name('forgot.password');

		// Memproses Form (Kirim Email)
		Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
	});
});


Route::controller(App\Http\Controllers\TransactionController::class)
->prefix('transaction')
->as('transaction.')
->group(function () {
	Route::post('checkout', 'checkout')->name('checkout');
});


Route::controller(\App\Http\Controllers\PaymentController::class)
->prefix('payment')
->group(function () {
	// Payment Routes
	Route::get('checkout', 'checkout')->name('payment.checkout');
	Route::post('/payments/process', 'processPayment')->name('payment.process');
	Route::get('/invoice/{ref}', 'invoice')->name('pages.invoice');

	// Callback Duitku (WAJIB dikecualikan dari CSRF di VerifyCsrfToken middleware)
	// Route::post('/api/callback/duitku', [CallbackController::class, 'duitkuHandler'])->name('api.callback.duitku');
});

Route::controller(App\Http\Controllers\Pages\OrderController::class)

->group(function () {
	Route::as('topup.')
	->prefix('topup')
	->group(function () {
		Route::get('/{slug}', 'show')->name('show');
	});

	Route::as('checkout.')
	->prefix('checkout')
	->group(function () {
		Route::post('checkout', 'checkout')->name('store');
	});
});
// Route::middleware('role:member,reseller')->name('member.')->group(function () {

// Route::as('member.')
// ->group(function () {
// 	Route::controller(\App\Http\Controllers\Member\MemberDashboardController::class)
// 	->group(function () {
// 		Route::get('dashboard', 'index')->name('dashboard');
// 		Route::get('riwayat', 'index')->name('riwayat');
// 	});

// 	Route::controller(\App\Http\Controllers\Member\TransactionController::class)
// 	->group(function () {
// 		// Tambahkan di dalam group middleware auth
// 		Route::get('order/{category}', 'orderForm')->name('transaction.order');
// 	});

// 	Route::controller(\App\Http\Controllers\Admin\TransactionsController::class)
// 	->as('transactions.')
// 	->prefix('transactions')
// 	->group(function () {
// 		Route::get('/', 'index')->name('index');
// 		Route::get('form', 'form')->name('form');
// 		Route::post('store', 'store')->name('store');
// 		// Route::post('resend', 'resendJob')->name('resend');
// 		Route::get('detail/{transaction}', 'show')->name('show');
// 		Route::post('inquiry', 'pascaBayar')->name('inquiry');
// 	});

// 	Route::prefix('products')
// 	->as('products.')
// 	->group(function () {
// 		Route::controller(\App\Http\Controllers\Admin\Product\CategoryController::class)
// 		->prefix('categories')
// 		->as('categories.')
// 		->group(function () {
// 			// Route::get('/', 'index')->name('index');
// 			// Route::get('data', 'data')->name('data');
// 			// Route::get('show/{id}', 'show')->name('show');
// 			// Route::post('store', 'store')->name('store');
// 			// Route::post('update', 'update')->name('update');
// 			// Route::post('destroy', 'destroy')->name('destroy');
// 			// Route::post('assign', 'assignProducts')->name('assign');
// 			// Route::get('datatable/{id}/products', 'getProductsByCategory')->name('products');
// 			Route::get('category-by-parent', 'categoryByParent')->name('category-by-parent');
// 		});

// 		// Route::controller(\App\Http\Controllers\Admin\Product\BrandsController::class)
// 		// ->prefix('brands')
// 		// ->as('brands.')
// 		// ->group(function () {
// 		// 	Route::get('/', 'index')->name('index');
// 		// 	Route::get('data', 'data')->name('data');
// 		// 	Route::post('form', 'form')->name('form');
// 		// 	Route::post('store', 'store')->name('store');
// 		// });

// 		Route::controller(\App\Http\Controllers\Admin\Product\ProductsController::class)
// 		->group(function () {
// 			Route::prefix('items')
// 			->as('items.')
// 			->group(function () {
// 				// Route::get('/', 'index')->name('index');
// 				// Route::post('form', 'form')->name('form');
// 				// Route::post('store', 'store')->name('store');
// 				Route::post('get-products-by-category', 'getProductsByCategory')->name('getProductsByCategory');
// 				// Route::post('get-brands-by-category', 'getBrandsByCategory')->name('get-brands-by-category');
// 				// Route::get('search', 'search')->name('search');
// 			});

// 			// Route::prefix('categories')
// 			// ->as('categories.')
// 			// ->group(function () {
// 			// 	Route::get('/', 'index')->name('index');
// 			// });
// 		});
// 	});
// });

Route::middleware(['auth', 'is_active'])->group(function () {
	Route::prefix('admin')->as('admin.')
	->group(function () {
		Route::controller(\App\Http\Controllers\Admin\AdminController::class)
		->group(function () {
			Route::get('dashboard', 'dashboard')->name('dashboard');
		});

		Route::controller(\App\Http\Controllers\Admin\ProviderController::class)
		->as('providers.')
		->prefix('providers')
		->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('update', 'update')->name('update');
			Route::post('store', 'store')->name('store');
			Route::post('check-balance', 'checkBalance')->name('check-balance');
			Route::post('toggle-status', 'toggleStatus')->name('toggle-status');
		});

		Route::controller(\App\Http\Controllers\Admin\TransactionsController::class)
		->as('transactions.')
		->prefix('transactions')
		->group(function () {
			Route::get('/', 'index')->name('index');
			Route::get('form', 'form')->name('form');
			Route::post('store', 'store')->name('store');
			// Route::post('resend', 'resendJob')->name('resend');
			Route::get('detail/{transaction}', 'show')->name('show');
			Route::post('inquiry', 'pascaBayar')->name('inquiry');
		});

		Route::prefix('products')
		->as('products.')
		->group(function () {
			Route::controller(\App\Http\Controllers\Admin\Product\CategoryController::class)
			->prefix('categories')
			->as('categories.')
			->group(function () {
				Route::get('/', 'index')->name('index');
				Route::get('data', 'data')->name('data');
				Route::get('show/{id}', 'show')->name('show');
				Route::post('store', 'store')->name('store');
				Route::post('update', 'update')->name('update');
				Route::post('destroy', 'destroy')->name('destroy');
				Route::post('assign', 'assignProducts')->name('assign');
				Route::get('datatable/{id}/products', 'getProductsByCategory')->name('products');
				Route::get('category-by-parent', 'categoryByParent')->name('category-by-parent');
			});

			Route::controller(\App\Http\Controllers\Admin\Product\BrandsController::class)
			->prefix('brands')
			->as('brands.')
			->group(function () {
				Route::get('/', 'index')->name('index');
				Route::get('data', 'data')->name('data');
				Route::post('form', 'form')->name('form');
				Route::post('store', 'store')->name('store');
			});

			Route::controller(\App\Http\Controllers\Admin\Product\ProductsController::class)
			->group(function () {
				Route::prefix('items')
				->as('items.')
				->group(function () {
					Route::get('/', 'index')->name('index');
					Route::post('form', 'form')->name('form');
					Route::post('store', 'store')->name('store');
					Route::post('get-products-by-category', 'getProductsByCategory')->name('getProductsByCategory');
					Route::post('get-brands-by-category', 'getBrandsByCategory')->name('get-brands-by-category');
					Route::get('search', 'search')->name('search');
				});

				// Route::prefix('categories')
				// ->as('categories.')
				// ->group(function () {
				// 	Route::get('/', 'index')->name('index');
				// });
			});
		});

		Route::controller(\App\Http\Controllers\Admin\MemberController::class)
		->as('members.')
		->prefix('members')
		->group(function () {
			Route::get('/', 'index')->name('index');
		});

	});
});


// Route::post('/payment/callback', 'PaymentController@callback');


// Route dinamis: menangkap apapun setelah /topup/ sebagai 'slug'
// Route::get('/topup/{slug}', function ($slug) {
// 	// Di real app, kamu akan query database di sini berdasarkan slug
// 	// $product = Product::where('slug', $slug)->first();

// 	return view('pages.topup.detail', ['slug' => $slug]);
// })->name('topup.detail');

// Halaman Detail (Contoh)
Route::get('/topup/mobile-legends', function () {
	return view('pages.topup.detail');
});

// Halaman Order History
Route::get('/order', function () {
	return view('pages.order.index');
});
