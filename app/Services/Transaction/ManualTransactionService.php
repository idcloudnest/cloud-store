<?php

namespace App\Services\Transaction;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\BalanceHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ManualTransactionService.
 */
class ManualTransactionService
{
	/**
	 * Proses Refund Transaksi
	 * Mengembalikan saldo user, update status transaksi, dan catat history.
	 */
	public function processRefund(Transaction $transaction, $amount, $reason)
	{
		$user = User::find($transaction->user_id);

		if ($user) {
			$transaction->update([
				'payment_status' => 'refunded',
				'delivery_status'  => 'failed',
				'provider_message' => $reason
			]);

			$user->balance += $amount;
			$user->save();

			BalanceHistory::create([
				'user_id'      => $user->id,
				'type'         => 'debit', // Debit = Uang Masuk ke User
				'amount'       => $amount,
				'description'  => "Refund Gagal: {$transaction->invoice} ({$reason})",
				'last_balance' => $user->balance
			]);

			Log::info("REFUND_PROCESSED: Invoice {$transaction->invoice}, Amount: {$amount}, Reason: {$reason}");
		}
	}
}
