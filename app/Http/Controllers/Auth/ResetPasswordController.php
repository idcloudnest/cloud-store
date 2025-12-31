<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
	// Menampilkan Form Input Password Baru
	public function showResetForm(Request $request, $token = null)
	{
		return view('auth.passwords.reset')->with(
			['token' => $token, 'email' => $request->email]
		);
	}

	// Memproses Perubahan Password
	public function reset(Request $request)
	{
		// 1. Validasi Input
		$request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed|min:8',
		]);

		// 2. Proses Reset menggunakan Password Facade
		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		// \Log::debug(json_encode($status));
		// \Log::debug(json_encode(Password::PASSWORD_RESET));

		// 3. Cek Hasil
		if ($status === Password::PASSWORD_RESET) {
			// Jika Sukses, redirect ke Login dengan pesan sukses
			return redirect()->route('auth.login')->with('status', __($status));
		}

		// Jika Gagal (Token expired / Email salah)
		return back()
			->withInput($request->only('email'))
			->withErrors(['email' => __($status)]);
	}
}
