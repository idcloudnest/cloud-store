<?php

namespace App\Services\Provider;

interface ProviderInterface
{
	/**
	 * Mengambil data profile provider
	 */
	// public function profile(): array;

	/**
	 * Mengambil saldo terbaru dari provider
	 */
	public function checkBalance(): array;

	/**
	 * Mengambil daftar produk (pricelist)
	 */
	public function productList(): array;

	/**
	 * Melakukan transaksi pengisian
	 */
	// public function transaction(string $refId, string $skuCode, string $destination, array $options = []): array;

	/**
	 * Cek status transaksi (jika ada)
	 */
	public function checkStatus(string $refId): array;
}
