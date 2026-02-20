<?php

namespace App\Http\Controllers;

abstract class Controller
{
	use \App\Traits\ApiResponser;

	protected string $domain;

	public function __construct()
	{
		$this->domain = str_replace(
			['https://', 'http://'],
			'',
			config('app.url')
		);
	}
}
