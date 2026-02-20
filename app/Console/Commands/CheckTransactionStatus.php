<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Transaction\ManualTransactionService;
use App\Models\Transaction;
use App\Services\TelegramService;
use App\Services\Provider\ProviderFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckTransactionStatus extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:check-transaction-status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cek status transaksi (processing/pending) ke Provider, update status database, dan proses refund otomatis jika gagal.';

	/**
	 * Execute the console command.
	 */
	public function handle(ManualTransactionService $refundService, TelegramService $telegram)
	{
		Transaction::where('payment_status', 'paid')
			->whereIn('delivery_status', ['processing', 'pending'])
			->where('created_at', '>=', now()->subDays(30))
			->with(['product:id,type'])
			->chunkById(10, function ($transactions) use ($refundService) {
				foreach ($transactions as $trx) {
					try {
						$customerNo = trim($trx->customer_no . $trx->zone_id);
						$service = ProviderFactory::make('digiflazz');

						$commands = '';
						if (isset($trx->product->type) && strtolower($trx->product->type) === 'pascabayar') {
							$commands = 'status-pasca';
						}

						// --- HIT API ---
						// Untuk Prabayar: Kirim ulang RefID yg sama = Cek Status (Idempotent)
						// Untuk Pascabayar: Kirim commands 'status-pasca'
						$response = $service->transaction(
							refId: $trx->invoice,
							skuCode: $trx->sku_snapshot,
							destination: $customerNo,
							commands: $commands,
						);

						$data = $response['data'] ?? null;

						if ($data) {
							if ($data['rc'] === '00') {
								$trx->update([
									'delivery_status'  => 'success',
									'sn'               => $data['sn'],
									'provider_message' => $data['message'] ?? 'Transaksi Sukses',
								]);
								// Log::info("SCHEDULER: TRX {$trx->invoice} Updated to SUCCESS");
							}
							elseif ($data['rc'] === '03') {
								// Tidak perlu update DB jika status sudah 'processing',
								// atau update timestamp 'updated_at' untuk tanda masih dipantau
								// Log::info("SCHEDULER: TRX {$trx->invoice} Still Pending...");
								$telegram->sendTransactionError($trx, $data['message'] ?? "SCHEDULER: TRX {$trx->invoice} Still Pending...");
							}
							else {
								$failReason = $data['message'] ?? 'Gagal dari Provider';

								$telegram->sendTransactionError($trx, $failReason);

								$refundService->processRefund($trx, $trx->total_amount, $failReason);
							}
						}

					} catch (\Exception $e) {
						// Jangan stop loop hanya karena 1 transaksi error sistem
						$telegram->sendSystemError($trx->invoice, $e->getMessage());
						// Log::error("SCHEDULER ERROR TRX {$trx->invoice}: " . $e->getMessage());
					}
				}
			});
	}
}
