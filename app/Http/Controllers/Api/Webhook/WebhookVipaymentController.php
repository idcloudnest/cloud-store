<?php

namespace App\Http\Controllers\Api\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Provider\ProviderFactory;
use Vipayment\Vipayment;

class WebhookVipaymentController extends Controller
{
	public function handle()
	{
		// $vipayment = new Vipayment($apiId, $apiKey);
		// return $profile = $vipayment->profile();

		$service = ProviderFactory::make('vipayment');
		// $service = ProviderFactory::make('digiflazz');

		// return $service->checkBalance();
		return  $service->profile();
		// return  $service->productList();
		// return  $service->serviceGame();
		// return  $service->serviceGame('Ace Racer', 'available');
		// return  $service->checkUsername('mobile-legends', '135712564', '2674');
	}
}
