<?php

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
