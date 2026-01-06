<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseAuthController extends Controller
{
	use \App\Traits\ApiResponser;

	protected $auth;

	public function __construct()
	{
		// Inisialisasi Firebase Auth menggunakan file JSON
		$factory = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
		$this->auth = $factory->createAuth();
	}

	public function login(Request $request)
	{
		return $this->successResponse($request->all(), message: 'Berhasil');
		$idTokenString = $request->input('id_token');

		try {
			// 1. Verifikasi Token ke Firebase Server
			$verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
			$claims = $verifiedIdToken->claims();
			$uid = $claims->get('sub'); // Firebase UID

			// Ambil data user dari token
			$user_data = $this->auth->getUser($uid);
			$email = $user_data->email;
			$name = $user_data->displayName ?? 'User Firebase';

			// 2. Cari atau Buat User di Database Laravel
			$user = User::updateOrCreate(
				['email' => $email], // Kunci pencarian (bisa pakai firebase_uid juga)
				[
					'name' => $name,
					'firebase_uid' => $uid,
					'password' => bcrypt(str_random(16)), // Password dummy random
					'email_verified_at' => now(),
				]
			);

			// 3. Login Manual ke Laravel (Session)
			Auth::login($user);

			return response()->json([
				'status' => 'success',
				'message' => 'Login berhasil',
				'redirect' => url('/dashboard') // Ganti sesuai halaman tujuan
			]);

		} catch (FailedToVerifyToken $e) {
			return response()->json(['status' => 'error', 'message' => 'Token tidak valid: '.$e->getMessage()], 401);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => 'Error: '.$e->getMessage()], 500);
		}
	}
}
