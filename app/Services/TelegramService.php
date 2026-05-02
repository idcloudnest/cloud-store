<?php

namespace App\Services;

use App\Models\TelegramBot; // Import Model Bot
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class TelegramService.
 */
class TelegramService
{
	protected $bot; // Object Model Bot
	protected $botToken;
	protected $chatId;

	public function __construct()
	{
		// 1. Ambil Bot yang statusnya 'Active' dari Database
		$this->bot = TelegramBot::getActiveBot();

		if ($this->bot) {
			$this->botToken = $this->bot->token;
			$this->chatId   = $this->bot->default_chat_id;
		}
	}

	/**
	 * Kirim pesan teks sederhana ke Telegram
	 */
	public function sendMessage(string $message, string $type = 'info', ?int $transactionId = null): bool
	{
		if (!$this->bot || empty($this->botToken)) {
			Log::warning('Telegram Service: Tidak ada bot aktif ditemukan di database.');

			return false;
		}

		try {
			$url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

			$response = Http::timeout(10)->post($url, [
				'chat_id'    => $this->chatId,
				'text'       => $message,
				'parse_mode' => 'HTML',
			]);

			return $response->successful();
		} catch (\Exception $e) {
			Log::error("Telegram Service Exception: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Kirim notifikasi khusus Transaksi Gagal
	 */
	public function sendTransactionError($transaction, $reason)
	{
		$destination = $transaction->customer_no . ($transaction->zone_id ? " ($transaction->zone_id)" : "");
		$text = "🚨 <b>TRANSAKSI GAGAL</b> 🚨\n\n";
		$text .= "<b>Invoice:</b> #{$transaction->invoice}\n";
		$text .= "<b>Produk:</b> {$transaction->product_name_snapshot}\n";
		$text .= "<b>Tujuan:</b> {$destination}\n";
		$text .= "<b>User:</b> {$transaction->user?->name}\n\n"; // Asumsi ada relasi user
		$text .= "⚠️ <b>Penyebab:</b> {$reason}\n";
		$text .= "<i>Saldo telah dikembalikan (Refund).</i>";

		return $this->sendMessage($text);
	}

	public function sendTransactionPending($transaction, $reason = "")
	{
		$destination = $transaction->customer_no . ($transaction->zone_id ? " ($transaction->zone_id)" : "");

		$text = "⏳ <b>TRANSAKSI PENDING</b> ⏳\n\n";
		$text .= "<b>Invoice:</b> #{$transaction->invoice}\n";
		$text .= "<b>Produk:</b> {$transaction->product_name_snapshot}\n";
		$text .= "<b>Tujuan:</b> {$destination}\n";
		$text .= "<b>User:</b> {$transaction->user?->name}\n\n";
		$text .= "ℹ️ <b>Status:</b> {$reason}\n";

		return $this->sendMessage($text);
	}

	/**
	 * Kirim notifikasi Error System/Exception
	 */
	public function sendSystemError($transactionId, $errorMessage)
	{
		$text = "🔥 <b>SYSTEM ERROR (JOB)</b> 🔥\n\n";
		$text .= "<b>Trx ID:</b> {$transactionId}\n";
		$text .= "<b>Error:</b> <pre>{$errorMessage}</pre>";

		return $this->sendMessage($text);
	}

	public function sendInactiveProducts($products, $reason = "")
	{
		$text = "🚫 <b>PRODUK NONAKTIF</b> 🚫\n\n";
		$text .= $products;
		$text .= $reason;

		return $this->sendMessage($text);
	}
}
