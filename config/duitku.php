<?php

return [
	'merchant_code' => env('DUITKU_MERCHANT_CODE'),
	'api_key'       => env('DUITKU_API_KEY'),
	'env'           => env('DUITKU_ENV', 'sandbox'), // sandbox atau production
	'base_url'      => env('DUITKU_BASE_URL', 'https://passport.duitku.com/webapi/api/merchant/'), // sandbox atau production
	'callback_url'  => env('DUITKU_CALLBACK_URL'),
	'return_url'    => env('DUITKU_RETURN_URL'),
];
