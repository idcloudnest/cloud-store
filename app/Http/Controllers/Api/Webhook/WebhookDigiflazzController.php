<?php

namespace App\Http\Controllers\Api\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Webhook\DigiflazzCallbackService; // Import Service

class WebhookDigiflazzController extends Controller
{
	protected $digiflazzService;

	// Inject Service melalui Constructor
	public function __construct(DigiflazzCallbackService $digiflazzService)
	{
		$this->digiflazzService = $digiflazzService;
	}

	public function handle(Request $request)
	{
		\Log::debug(json_encode([
			'prefix' => 'HANDLE_CONTROLLER'
			'data' => $request->all(),
		], JSON_PRETTY_PRINT));
		// // 1. Validasi Payload Awal
		// $payload = $request->all();

		// // Validasi signature (X-Hub-Signature) sebaiknya tetap di Controller
		// // atau dibuat Middleware khusus agar Service benar-benar murni logic bisnis.
		// // (Anggap code validasi signature ada di sini)

		// if (!isset($payload['data'])) {
		// 	return response()->json(['status' => 'failed', 'message' => 'Invalid payload'], 400);
		// }

		// // 2. Panggil Service
		// $result = $this->digiflazzService->handleCallback($payload['data']);

		// // 3. Return Response sesuai hasil Service
		// if ($result['success']) {
		// 	return response()->json(['status' => 'ok', 'message' => $result['message']]);
		// } else {
		// 	return response()->json(['status' => 'failed', 'message' => $result['message']], $result['code']);
		// }
	}
}
