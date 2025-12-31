<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
	/**
	 * Handle an incoming request.
	 * Parameter ...$roles menangkap argumen role yang kita tulis di route.
	 */
	public function handle(Request $request, Closure $next, ...$roles): Response
	{
		// Pastikan user login
		if (! $request->user()) {
			return redirect()->route('auth.login');
		}

		// Cek apakah role user ada di dalam daftar yang diizinkan
		// Contoh logic: if (! in_array('admin', ['admin', 'reseller']))
		if (! in_array($request->user()->role, $roles)) {

			// Jika request API/Json
			if ($request->expectsJson()) {
				return response()->json(['message' => 'Unauthorized Access'], 403);
			}

			// Jika Web, tampilkan halaman 403 Forbidden
			abort(403, 'Anda tidak memiliki akses ke halaman ini.');
		}

		return $next($request);
	}
}
