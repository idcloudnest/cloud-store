<?php

namespace App\Services\Webhook;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
/**
 * Class DigiflazzCallbackService.
 */
class DigiflazzCallbackService
{
	/**
	 * Handle logika update transaksi dari callback
	 */
	public function handleCallback(array $data)
	{
		$refId = $data['ref_id'] ?? null;
		$status = $data['status'] ?? null; // Sukses, Gagal, Pending
		$sn = $data['sn'] ?? null;
		$rc = $data['rc'] ?? null;
		$message = $data['message'] ?? null;

		// 1. Cari Transaksi
		$transaction = Transaction::where('ref_id', $refId)->first();

		if (!$transaction) {
			Log::warning("Webhook: Transaksi $refId tidak ditemukan.");
			// Return false atau throw exception agar controller tahu ini 404
			return ['success' => false, 'code' => 404, 'message' => 'Transaction not found'];
		}

		// 2. Cek Double Update (Idempotency)
		if (in_array($transaction->status, ['success', 'failed'])) {
			return ['success' => true, 'code' => 200, 'message' => 'Already processed'];
		}

		// 3. Proses Update di Database
		try {
			DB::beginTransaction();

			if ($status === 'Sukses') {
				$transaction->update([
					'status' => 'success',
					'sn' => $sn,
					'response_code' => $rc,
				]);

				// Opsional: Panggil service lain, misal NotificationService
				// $this->notificationService->sendSuccessWA($transaction);

			} elseif ($status === 'Gagal') {
				$transaction->update([
					'status' => 'failed',
					'response_code' => $rc,
					'note' => $message,
				]);

				// Opsional: Logic Refund Saldo User
				// $this->balanceService->refund($transaction->user_id, $transaction->amount);
			}

			DB::commit();

			return ['success' => true, 'code' => 200, 'message' => 'Transaction updated'];

		} catch (\Exception $e) {
			DB::rollBack();
			Log::error("Error Service Digiflazz: " . $e->getMessage());
			return ['success' => false, 'code' => 500, 'message' => 'Internal Server Error'];
		}
	}
}
