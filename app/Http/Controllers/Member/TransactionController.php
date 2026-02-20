<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
	public function orderForm($category)
	{
		// Logic sementara: Harusnya ambil produk dari DB berdasarkan kategori
		// Di sini kita kirim data dummy dulu biar UI-nya jadi
		$categoryTitle = ucfirst($category); // Misal: Pulsa

		// Contoh data dummy produk
		$products = [
			['code' => 'TSEL5', 'name' => 'Telkomsel 5.000', 'price' => 5500, 'type' => 'Telkomsel'],
			['code' => 'TSEL10', 'name' => 'Telkomsel 10.000', 'price' => 10500, 'type' => 'Telkomsel'],
			['code' => 'ISAT5', 'name' => 'Indosat 5.000', 'price' => 5800, 'type' => 'Indosat'],
			['code' => 'ISAT10', 'name' => 'Indosat 10.000', 'price' => 10800, 'type' => 'Indosat'],
			// ... tambah data dummy lain
		];

		return view('member.transactions.order', compact('category', 'categoryTitle', 'products'));
	}
}
