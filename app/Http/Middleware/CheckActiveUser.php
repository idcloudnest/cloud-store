<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckActiveUser
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		// 1. Cek apakah user sedang login dan statusnya TIDAK aktif
		if (Auth::check() && !Auth::user()->is_active) {

			// 2. Proses Logout Paksa
			Auth::logout();
			$request->session()->invalidate();
			$request->session()->regenerateToken();

			// 3. Respon untuk API (JSON)
			if ($request->expectsJson()) {
				return response()->json([
					'meta' => [
						'code' => 403,
						'status' => 'error',
						'message' => 'Akun Anda telah dinonaktifkan/suspend.'
					],
					'data' => null
				], 403);
			}

			// 4. Respon untuk Web (Redirect)
			return redirect()->route('auth.login')->withErrors([
				'identity' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Admin.',
			]);
		}

		return $next($request);
	}
}
