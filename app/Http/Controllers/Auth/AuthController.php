<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules; // Untuk validasi password default Laravel
use App\Models\User;

class AuthController extends Controller
{
	public function showLoginForm()
	{
		return view('auth.login');
	}

	public function login(Request $request)
	{
		// 1. Validasi Input
		$request->validate([
			'identity' => 'required|string', // Bisa Email atau Username
			'password' => 'required|string',
		]);

		// 2. Tentukan apakah input berupa Email atau Username
		$loginType = filter_var($request->input('identity'), FILTER_VALIDATE_EMAIL)
			? 'email'
			: 'username';

		// 3. Siapkan Kredensial
		// Kita tambahkan 'is_active' => true agar user yang disuspend tidak bisa login
		$credentials = [
			$loginType  => $request->input('identity'),
			'password'  => $request->input('password'),
			'is_active' => true
		];

		// 4. Eksekusi Login (Auth::attempt otomatis hash checking)
		$remember = $request->filled('remember');

		if (Auth::attempt($credentials, $remember)) {
			// PENTING: Regenerate session ID untuk mencegah Session Fixation Attack
			$request->session()->regenerate();

			// --- LOGIC BARU: CEK ROLE ---
			$user = Auth::user(); // Ambil data user yang baru saja login

			// Cek apakah role-nya admin
			// (Sesuaikan 'role' dan 'admin' dengan nama kolom & value di database Anda)
			if ($user->role === 'admin') {
				return redirect()->intended(route('admin.dashboard'))
					->with('success', 'Halo Admin, Selamat bekerja!');
			}

			// Jika bukan admin (misal: member), lempar ke dashboard member
			return redirect()->intended(route('member.dashboard'))
				->with('success', 'Selamat datang kembali!');

			// return redirect()->intended('admin/dashboard') // Redirect ke halaman yang ingin dituju sebelumnya
			// 	->with('success', 'Selamat datang kembali!');
		}

		// 5. Jika Gagal
		// Cek dulu apakah gagal karena password salah atau karena akun suspend
		// (Opsional: logic ini bisa diperdalam, tapi return error umum lebih aman)

		return back()->withErrors([
			'identity' => 'Kredensial tidak cocok atau akun sedang dinonaktifkan.',
		])->onlyInput('identity');
	}

	public function logout(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('auth/login')->with('success', 'Anda telah logout.');
	}

	public function showRegisterForm()
	{
		return view('auth.register');
	}

	public function register(Request $request)
	{
		// 1. Validasi Input
		$request->validate([
			'name'      => ['required', 'string', 'max:255'],
			'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'phone'     => ['required', 'string', 'max:15', 'unique:users'], // Penting untuk Server Pulsa
			'username'  => ['required', 'string', 'max:20', 'alpha_dash', 'unique:users'],
			'password'  => ['required', 'confirmed', Rules\Password::defaults()], // confirmed = cek field password_confirmation
			// 'pin'       => ['required', 'digits:6', 'numeric'], // PIN Wajib 6 Angka
			'terms' => 'accepted',
		], [
			// Custom Error Message (Opsional)
			'phone.unique' => 'Nomor HP ini sudah terdaftar sebagai mitra.',
			'username.unique' => 'Username sudah digunakan.',
			// 'pin.digits'   => 'PIN Transaksi harus terdiri dari 6 angka.',
		]);

		// 2. Buat User Baru
		$user = User::create([
			'name'      => $request->name,
			'email'     => $request->email,
			'phone'     => $request->phone,
			'username'  => $request->username,
			'password'  => $request->password, // Model akan otomatis hash (krn casts 'hashed')
			// 'pin'       => $request->pin,      // Model akan otomatis hash (krn casts 'hashed')
			'role'      => 'member',           // Default role
			'balance'   => 0,
			'is_active' => true,

			// Generate API Key Otomatis (sesuai diskusi sebelumnya)
			'api_key'   => 'sk_live_' . bin2hex(random_bytes(32)),
		]);

		// 3. Login Otomatis setelah sukses daftar
		Auth::login($user);

		// 4. Regenerate Session & Redirect
		$request->session()->regenerate();

		return redirect()->route('member.dashboard')
			->with('success', 'Pendaftaran berhasil! Selamat bergabung, Mitra ' . $user->name);
	}
}
