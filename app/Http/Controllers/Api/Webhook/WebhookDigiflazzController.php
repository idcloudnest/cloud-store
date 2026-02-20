<?php

namespace App\Http\Controllers\Api\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Webhook\DigiflazzCallbackService; // Import Service
use App\Services\Transaction\ManualTransactionService;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookDigiflazzController extends Controller
{
	use \App\Traits\ApiResponser;
	protected $digiflazzService;

	// Inject Service melalui Constructor
	public function __construct(DigiflazzCallbackService $digiflazzService, ManualTransactionService $manualTransactionService)
	{
		$this->digiflazzService = $digiflazzService;
		$this->manualTransactionService = $manualTransactionService;
	}

	public function handle(Request $request)
	{
		$payload = $request->input('data');

		// Log Request Masuk (Laravel otomatis handle json_encode di context)
		Log::debug('WEBHOOK_DIGIFLAZZ_INCOMING', ['payload' => $payload]);

		if (empty($payload))
			return response()->json(['status' => 'failed', 'message' => 'Empty Data'], 400);

		DB::beginTransaction();
		try {
			// lockForUpdate mencegah data diedit oleh proses lain sampai proses ini selesai (commit/rollback)
			$transaction = Transaction::where('ref_id', $payload['ref_id'])
				->lockForUpdate()
				->first();

			if (!$transaction) {
				Log::warning('WEBHOOK_TRX_NOT_FOUND', ['ref_id' => $payload['ref_id'] ?? 'null']);
				DB::rollBack();
				return response()->json(['status' => 'failed', 'message' => 'Transaction not found'], 404);
			}

			$transaction->provider_message = $payload['message'] ?? null;
			$transaction->wa_supplier      = $payload['wa'] ?? null;
			$transaction->tele_supplier    = $payload['tele'] ?? null;
			$transaction->sn               = $payload['sn'] ?? null;

			// RC '00' = Sukses
			// RC '03' = Pending (Biasanya)
			// Selain itu = Gagal
			if (isset($payload['rc']) && $payload['rc'] === '00') {
				$transaction->delivery_status = 'success';

				// OPSIONAL: Jika status sebelumnya failed/pending dan sekarang sukses,
				// pastikan logic update saldo reseller (jika ada) ditangani di sini.

			} elseif (isset($payload['rc']) && $payload['rc'] === '03') {
				$transaction->delivery_status = 'processing';
			} else {
				if ($transaction->delivery_status !== 'failed') {

					$transaction->delivery_status = 'failed';

					Log::warning('WEBHOOK_TRANSACTION_FAILED', [
						'ref_id' => $payload['ref_id'],
						'rc' => $payload['rc'] ?? 'unknown',
						'msg' => $payload['message'] ?? ''
					]);

					// Panggil Refund Service
					// Pastikan variable amount yang dipakai benar (total_amount atau amount)
					$this->manualTransactionService->processRefund(
						$transaction,
						$transaction->total_amount,
						$payload['message'] ?? 'Transaksi gagal dari webhook'
					);
				}
			}

			$transaction->save();

			DB::commit();

			return response()->json(['status' => 'success']);
		} catch (\Throwable $th) {
			DB::rollBack();

			Log::error('WEBHOOK_DIGIFLAZZ_ERROR', [
				'message' => $th->getMessage(),
				'line' => $th->getLine(),
				'file' => $th->getFile()
			]);

			return response()->json(['status' => 'error', 'message' => 'Server Error'], 500);
		}
	}
}
