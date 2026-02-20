<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Queueable;

use App\Models\Transaction;
use App\Models\Provider;
use App\Services\Transaction\ManualTransactionService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Services\Provider\ProviderFactory;
use App\Services\TelegramService;

class ProcessTransactionToProvider implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $transactionId;

	// Waktu tunggu maksimal sebelum Job dianggap gagal (detik)
	public $timeout = 60;

	// Berapa kali boleh dicoba ulang jika gagal/timeout
	public $tries = 3;

	/**
	 * Create a new job instance.
	 */
	public function __construct($transactionId)
	{
		$this->transactionId = $transactionId;
	}

	/**
	 * Execute the job.
	 */
	public function handle(ManualTransactionService $refundService, TelegramService $telegram): void
	{
		// Log::info("Job Start: Memproses TRX ID {$this->transactionId}");
		$transaction = Transaction::where('id', $this->transactionId)->with(['user', 'product'])->first();

		if (!$transaction) {
			Log::error("Job Transaksi Gagal: Transaksi ID {$this->transactionId} tidak ditemukan.");
			return;
		}

		// Cek jika status sudah bukan pending (misal sudah diproses job lain/double job)
		if ($transaction->delivery_status !== 'pending') {
			return;
		}

		try {
			// Gabungkan Nomor Tujuan
			$customerNo = trim($transaction->customer_no . $transaction->zone_id);

			$commands = ''; // Default kosong untuk Prabayar
			if (isset($transaction->product->type) && strtolower($transaction->product->type) === 'pascabayar') {
				$commands = 'pay-pasca';
			}

			// Hit API Provider
			$service = ProviderFactory::make('digiflazz');

			$response = $service->transaction(
				refId: $transaction->invoice,
				skuCode: $transaction->sku_snapshot,
				destination: $customerNo,
				commands: $commands, // <--- bayar tagihan
			);

			$apiData = $response['data'] ?? null;

			// KASUS 1: Tidak ada respon dari API / Error Koneksi
			if (empty($apiData)) {
				$reason = 'No Response from Provider (Empty Data)';
				$refundService->processRefund($transaction, $transaction->total_amount, $reason);
				$telegram->sendTransactionError($transaction, $reason);
				return;
			}

			// Cek RC (00 = Sukses, 03 = Pending)
			if (in_array($apiData['rc'], ['00', '03'])) {
				$status = ($apiData['rc'] === '00') ? 'success' : 'processing';

				$transaction->update([
					'delivery_status'  => $status,
					'sn'               => $apiData['sn'] ?? null,
					'provider_message' => $apiData['message'] ?? 'Diproses Provider',
				]);
			}
			// Transaksi Ditolak Provider (Gagal Langsung)
			else {
				$reason = $apiData['message'] ?? 'Gagal dari Provider (RC Unknown)';
				$refundService->processRefund($transaction, $transaction->total_amount, $reason);
				$telegram->sendTransactionError($transaction, $reason);
			}

		} catch (\Exception $e) {
			// Error coding / logic di dalam Job
			Log::error("Job Exception ID {$transaction->id}: " . $e->getMessage());
			$telegram->sendSystemError($transaction->invoice, $e->getMessage());

			// Set status error/retry logic jika perlu
			// Jangan langsung refund di sini jika ingin menggunakan fitur 'retry' bawaan Laravel Queue
			throw $e;
		}
	}
}
