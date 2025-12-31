<?php

namespace App\Http\Controllers\Api\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookMidtransController extends Controller
{
	// Fungsi Callback (Webhook) untuk menerima status dari Midtrans
	public function handle(Request $request)
	{
		\Log::debug(json_encode($request->all(), JSON_PRETTY_PRINT));
		$serverKey = config('midtrans.server_key');
		$hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

		if($hashed == $request->signature_key){
			if($request->transaction_status == 'capture' || $request->transaction_status == 'settlement'){
				$transaction = Transaction::where('order_id', $request->order_id)->first();
				if($transaction){
					$transaction->update(['status' => 'success']);
					// DISINI LOGIKA KIRIM DIAMOND KE USER (API GAME)
				}
			}
		}
	}
}
