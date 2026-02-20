<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
	// /*
	// |--------------------------------------------------------------------------
	// | Password Reset Controller
	// |--------------------------------------------------------------------------
	// |
	// | Controller ini bertanggung jawab untuk menangani email reset password
	// | dan mengirimkan notifikasi ke user. Kita menggunakan Trait bawaan
	// | Laravel agar aman dan cepat.
	// |
	// */

	// use SendsPasswordResetEmails;

	// /**
	//  * Menampilkan form untuk meminta link reset password.
	//  *
	//  * @return \Illuminate\View\View
	//  */
	// public function showLinkRequestForm()
	// {
	// 	// Arahkan ke file view yang baru saja Anda buat
	// 	// Lokasi: resources/views/auth/passwords/email.blade.php
	// 	return view('auth.passwords.email');
	// }

	/**
	 * Menampilkan form untuk meminta link reset password.
	 */
	public function showLinkRequestForm()
	{
		return view('auth.passwords.email');
	}

	/**
	 * Memproses pengiriman link reset password ke email.
	 * (Pengganti Trait SendsPasswordResetEmails)
	 */
	public function sendResetLinkEmail(Request $request)
	{
		// 1. Validasi Input
		$request->validate(['email' => 'required|email']);

		// 2. Kirim Link menggunakan Password Facade bawaan Laravel
		// Kita akan mengirim link ke email yang diinputkan
		$status = Password::sendResetLink(
			$request->only('email')
		);

		// 3. Cek Status Pengiriman
		if ($status === Password::RESET_LINK_SENT) {
			// Jika Berhasil: Kembali dengan pesan sukses
			return back()->with(['status' => __($status)]);
		}

		// Jika Gagal: Kembali dengan error
		return back()->withErrors(['email' => __($status)]);
	}
}
