<?php

if (!function_exists('formatRibuan')) {
	/**
	 * Format angka dengan pemisah ribuan tanpa Rp
	 *
	 * @param  mixed  $number
	 * @return string
	 */
	function formatRibuan($number): string
	{
		if (!is_numeric($number)) {
			return '0';
		}

		return number_format($number, 0, '.', ',');
	}
}

if (!function_exists('formatRupiah')) {
	/**
	 * Format angka ke Rupiah
	 *
	 * @param  mixed  $number
	 * @return string
	 */
	function formatRupiah($number)
	{
		if (!is_numeric($number)) {
			return 'Rp 0';
		}

		// number_format(angka, jumlah_desimal, pemisah_desimal, pemisah_ribuan)
		return "Rp " . number_format($number, 0, ',', '.');
	}
}

if (!function_exists('onlyNumber')) {
	/**
	 * Format angka ke Rupiah
	 *
	 * @param  mixed  $number
	 * @return int
	 */
	function onlyNumber($number): int
	{
		return preg_replace('/\D/', '', $number);
	}
}

if (!function_exists('assetParse')) {
	/**
	 * Format angka ke Rupiah
	 *
	 * @param  mixed  $url
	 * @return string
	 */
	function assetParse(?string $path = ''): string
	{
		if (!$path)
			return '';

		$appUrl = config('app.url') ?: url('/');
		$host   = parse_url($appUrl, PHP_URL_HOST);
		return 'https://assets.idcloudnest.com/'.preg_replace(
			'#^' . preg_quote($host, '#') . '/#',
			'',
			$path
		);
	}
}
