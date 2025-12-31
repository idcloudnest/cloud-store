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
		// 1. Ambil Secret Key dari .env
		$secretKey = env('DIGIFLAZZ_SECRET_KEY');

		// 2. Ambil Signature dari Header yang dikirim Digiflazz
		// Header ini berisi hasil hash yang dikirim Digiflazz
		$incomingSignature = $request->header('X-Hub-Signature');
		// Log::debug($incomingSignature);

		// 3. Ambil Raw Body (Isi pesan mentah)
		// PENTING: Jangan pakai $request->all(), harus getContent() agar akurat
		$payload = $request->getContent();
		Log::debug(json_encode([
			'prefix' => 'HEADER_DIGIFLAZZ',
			'data' => $request->header(),
		], JSON_PRETTY_PRINT));

		Log::debug(json_encode([
			'prefix' => 'PAYLOAD_DIGIFLAZZ',
			'data' => $request->all(),
		], JSON_PRETTY_PRINT));

		// 4. Hitung Signature sendiri menggunakan Secret Key kita
		// Rumus: HMAC-SHA1 dari Payload + Secret Key
		$calculatedSignature = 'sha1=' . hash_hmac('sha1', $payload, $secretKey);
		// Log::info($calculatedSignature);

		// --- DEBUGGING (Hapus ini jika sudah production) ---
		// Log::info('Digiflazz Debug:', [
		//     'incoming' => $incomingSignature,
		//     'calculated' => $calculatedSignature
		// ]);
		// ---------------------------------------------------

		// 5. Bandingkan Signature
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
