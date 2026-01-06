<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class VerifyDigiflazzSignature
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$secretKey = env('DIGIFLAZZ_SECRET_KEY');

		// Header ini berisi hasil hash yang dikirim Digiflazz
		$incomingSignature = $request->header('X-Hub-Signature');

		// PENTING: Jangan pakai $request->all(), harus getContent() agar akurat
		$payload = $request->getContent();
		Log::debug('HEADER_DIGIFLAZZ', ['header' => $request->header()]);

		Log::debug('PAYLOAD_DIGIFLAZZ', ['payload' => $request->input('data')]);

		// Rumus: HMAC-SHA1 dari Payload + Secret Key
		$calculatedSignature = 'sha1=' . hash_hmac('sha1', $payload, $secretKey);

		// --- DEBUGGING (Hapus ini jika sudah production) ---
		// Log::info('Digiflazz Debug:', [
		//     'incoming' => $incomingSignature,
		//     'calculated' => $calculatedSignature
		// ]);
		// ---------------------------------------------------

		// hash_equals digunakan untuk mencegah timing attack
		if (!hash_equals($calculatedSignature, (string) $incomingSignature)) {
			Log::warning('Percobaan akses Webhook Digiflazz dengan signature salah!');
			return response()->json([
				'status' => 'error',
				'message' => 'Unauthorized: Invalid Signature'
			], 401);
		}

		return $next($request);
	}
}
