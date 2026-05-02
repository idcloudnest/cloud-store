<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use SawitDB;
use SawitLog;
use Carbon\Carbon;

// Helpers
use App\Helpers\DateQueryHelper;

// Jobs
use App\Jobs\WriteSawitLogJob;

// Models
use App\Models\Product;
use App\Sawit\Models\Log as LogModel;

class ProductsController extends Controller
{
	public function index(Request $request)
	{
		// $log = LogModel::
		// // where('created_at', '2026-01-11T15:00:54+00:00')->
		// // get();
		// // orderBy('created_at', 'desc')->
		// first();
		// find('2026-01-11T15:01:18+00:00');
		// return $log;
		// $errors = LogModel::query()
		// // ->where('level', 'error')
		// // ->orderBy('created_at')
		// // ->limit(10)
		// ->first();
		// return response()->json($errors);
		// Log::create([
		// 	'type' => 'SYSTEM',
		// 	'level' => 'error',
		// 	'message' => 'Undefined variable $b',
		// 	'context' => [
		// 		'product_id' => 113,
		// 	],
		// 	'created_at' => now(),
		// ]);
		// SawitLog::error(
		// 	type: 'SYSTEM',
		// 	message: $th->getMessage(),
		// 	context: $request->all(),
		// 	refId: $request->product_id,
		// );

		// return $threshold = now()->subDays(30)->toDateTimeString();
		// $threshold = '2026-01-11 04:13:58';
		// $sawit = SawitDB::query("PANEN * DARI cloud_nest_logs");
		// $sawit = SawitDB::query("PANEN * DARI cloud_nest_logs DIMANA time <= '{$threshold}'");

		// return $range = DateQueryHelper::whereDate(now());
		// return now('Asia/Jakarta')->toIso8601String();
		// return $range = DateQueryHelper::whereDate('2026-01-10');
		// return now();

		// $query = DateQueryHelper::whereDateSql(
		// 	column: 'created_at',
		// 	date: now()->subDay(0)
		// 	// date: now()->addDay(1)
		// );

		// return SawitDB::table('logs')
		// 	->select('id', 'type', 'level', 'message', 'created_at')
		// 	->get();

		// $sawit = SawitDB::query("PANEN * DARI logs DIMANA {$query}");
		// $sawit = SawitDB::query("PANEN * DARI logs");
		// // // $sawit = SawitDB::query("GUSUR DARI logs DIMANA level = 'error'");
		// return $sawit;

		// return SawitDB::query("LAHAN logs;
		// 	TANAM KE logs (
		// 		id AUTO,
		// 		type STRING,
		// 		ref_id STRING,
		// 		message STRING,
		// 		context JSON,
		// 		level STRING,
		// 		created_at DATETIME
		// 	)
		// ");
		// return SawitDB::query("BAKAR LAHAN cloud_nest_logs");
		// return SawitDB::query("LIHAT LAHAN");
		// SawitLog::error(
		// 	type: 'payment.failed',
		// 	message: 'Timeout from provider',
		// 	context: [
		// 		'provider' => 'TELKOMSEL',
		// 		'timeout_ms' => 3000,
		// 	],
		// 	refId: 1111,
		// );
		// return "Ok";

		if ($request->ajax()) {
			$data = Product::with('brand','category:id,name');

			return DataTables::of($data)
				// Menambahkan kolom nomor urut otomatis (DT_RowIndex)
				->addIndexColumn()
				->orderColumn('buyer_sku_code', function ($query, $order) {
					// // Trik 1: Urutkan berdasarkan Panjang String dulu, baru nilainya
					// // ML5 (3 char) akan muncul sebelum ML10 (4 char)
					// $query->orderByRaw("LENGTH(buyer_sku_code) $order, buyer_sku_code $order");

					// Ambil hanya angkanya, ubah jadi Integer, lalu urutkan
					$query->orderByRaw("CAST(REGEXP_REPLACE(buyer_sku_code, '[^0-9]', '') AS UNSIGNED) $order");
				})
				->addColumn('harga', function ($row) {
					$html = '<div class="d-flex justify-content-between">';
					$html .= '<span class="badge badge-soft-primary fw-bold px-2 py-1">' . formatRupiah($row->price) . '</span>';
					$html .= '<span class="badge badge-soft-success fw-bold px-2 py-1">' . formatRupiah($row->selling_price) . '</span>';
					$html .= '</div>';
					return $html;
				})
				->editColumn('price', function ($row){
					// return '<span class="badge badge-soft-primary fw-bold px-2 py-1">' . formatRupiah($row->price) . '</span>';
					$modal = $row->price;
					$jual = $row->selling_price;
					$margin = $jual - $modal;
					$marginLow = $margin <= 0;
					$color = $marginLow ? 'warning' : 'info';
					$sign = $marginLow ? '- ' : '+ ';

					$html = '<div class="d-flex flex-column">';
					$html .= '<small class="text-muted" style="font-size: 0.7rem;">Modal: '.formatRupiah($modal).'</small>';
					$html .= '<div class="d-flex align-items-center">';
					$html .= '<span class="price-sell text-danger me-1">'.formatRupiah($jual).'</span>';
					// $html .= '<small class="text-decoration-line-through text-muted" style="font-size: 0.7rem;">22k</small>';
					$html .= '</div>';
					$html .= '<span class="profit-text text-'. $color .'"><i class="fa-solid fa-fire me-1"></i>Tipis ('. $sign . formatRupiah(abs($margin)).')</span>';
					$html .= '</div>';
					return $html;
				})
				->editColumn('selling_price', function ($row){
					return  '<span class="badge badge-soft-success fw-bold px-2 py-1">' . formatRupiah($row->selling_price) . '</span>';
				})
				->editColumn('status', fn($row) => $row->status && $row->seller_product_status ? '<span class="badge badge-soft-success">AKTIF</span>' : '<span class="badge badge-soft-danger">NON-AKTIF</span>')
				// Menambahkan kolom custom 'action' (tombol edit/hapus)
				->addColumn('action', function($row){
					// $btn = '<div class="dropdown text-end">';

					// // HAPUS: data-bs-display="static" (Ini biang keladinya)
					// // GANTI DENGAN: data-bs-popper-config='{"strategy":"fixed"}'

					// $btn .= '<button class="btn btn-light btn-sm action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config=\'{"strategy":"fixed"}\'>';
					// $btn .= '<i class="fa-solid fa-ellipsis-vertical"></i>';
					// $btn .= '</button>';

					// $btn .= '<ul class="dropdown-menu dropdown-menu-end border-0 shadow">';
					// $btn .= '<li><a class="dropdown-item btn-detail" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-eye me-2 text-primary"></i> Details</a></li>';
					// $btn .= '<li><a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-print me-2 text-secondary"></i> Cetak</a></li>';

					// $btn .= '</ul>';
					// $btn .= '</div>';

					$html = '<div class="dropdown text-end">';
					$html .= '<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config=\'{"strategy":"fixed"}\'><i class="fa-solid fa-ellipsis-vertical"></i></button>';
					$html .= '<ul class="dropdown-menu dropdown-menu-end border-0 shadow">';
					$html .= '<li><a class="dropdown-item btn-edit-product" href="javascript:(0)" data-id="'.$row->id.'"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Produk</a></li>';
					$html .= '<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>';
					$html .= '</ul>';
					$html .= '</div>';

					return $html;
				})
				->addColumn('total_rupiah', function($row){
					// Ini akan memanggil Accessor 'totalRupiah' di model Anda
					return formatRupiah($row->total_amount);
				})
				// Format kolom tanggal (opsional, biar rapi)
				->editColumn('updated_at', function($row){
					// return $row->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB';
					return $row->updated_at->diffForHumans();
				})
				->editColumn('payment_status', function($row){
					$status = $row->payment_status;

					$color = 'secondary'; // Default warna abu-abu
					switch ($status) {
						case 'paid':
							$color = 'success'; // Hijau
							break;
						case 'unpaid':
							$color = 'warning'; // Kuning
							break;
						case 'expired':
						case 'failed':
							$color = 'danger';  // Merah
							break;
						case 'refunded':
							$color = 'info';    // Biru muda
							break;
					}

					// Return HTML Badge
					return '<span class="badge bg-'.$color.' fw-normal px-2 py-1">'.strtoupper($status).'</span>';
					// 'unpaid','paid','expired','failed','refunded'
					return $row->payment_status;
				})
				->editColumn('delivery_status', function($row){
					// 'pending','processing','success','failed'
					// return $row->delivery_status;
					$status = $row->delivery_status;

					$color = 'secondary';
					switch ($status) {
						case 'success':
							$color = 'success'; // Hijau
							break;
						case 'pending':
							$color = 'warning'; // Kuning
							break;
						case 'processing':
							$color = 'primary'; // Biru
							break;
						case 'failed':
							$color = 'danger';  // Merah
							break;
					}

					// Return HTML Badge dengan sedikit margin agar rapi
					return '<span class="badge bg-'.$color.' fw-normal px-2 py-1">'.strtoupper($status).'</span>';
				})
				// ->addColumn('code_sort', fn($row) => (int) preg_replace('/[^0-9]/', '', $row->buyer_sku_code))

				// Wajib memberitahu kolom mana yang mengandung HTML agar tidak di-escape
				->rawColumns(['action', 'status', 'harga', 'price', 'selling_price', 'delivery_status'])

				// Finalisasi
				->make(true);
		}

		return view('admin.product.index');
	}

	public function form(Request $request)
	{
		try {
			$id = $request->product_id;
			$title = $id ? 'Edit Produk' : 'Tambah Produk';
			$product = Product::with('brand','category','provider')->find($request->product_id);

			$content = view('admin.product.form', compact('product','title'))->render();
			return $this->successResponse(['content' => $content], 'Ok');
		} catch (\Throwable $e) {
			return $this->errorResponse($e->getMessage());
		}
	}

	public function store(Request $request)
	{
		// return $this->successResponse([
		// 	'product_name'           => $request->product_name,
		// 	'selling_price'          => onlyNumber($request->selling_price),
		// 	'description'            => $request->description,
		// 	'status'                 => $request->product_status ? true : false,
		// 	'min_value'              => onlyNumber($request->min_value),
		// 	'max_value'              => onlyNumber($request->max_value),
		// ], 'Ok.');
		// WriteSawitLogJob::dispatch([
		// 	'time' => now(),
		// 	'level' => 'ERROR',
		// 	'context' => 'payment',
		// 	'sku' => 'TELKOMSEL',
		// 	'product' => 'DATA_20GB',
		// 	'message' => 'Timeout',
		// 	'response_ms' => 3200,
		// 	'error_type' => 'TIMEOUT',
		// ]);
		DB::beginTransaction();
		try {
			$product = Product::updateOrCreate(
				['id' => $request->product_id], // Cek berdasarkan SKU (Unik)
				[
					'product_name'           => $request->product_name,
					'selling_price'          => onlyNumber($request->selling_price),
					'description'            => $request->description,
					'status'                 => $request->product_status ? true : false,
					'min_value'              => onlyNumber($request->min_value),
					'max_value'              => onlyNumber($request->max_value),
				]
			);

			DB::commit();

			return $this->successResponse($product, 'Data berhasil disimpan.');
		} catch (\Throwable $th) {
			DB::rollback();

			SawitLog::error(
				type: 'SYSTEM',
				message: $th->getMessage(),
				context: $request->all(),
				refId: $request->product_id,
			);

			return $this->errorResponse($th->getMessage());
		}
	}

	public function getBrandsByCategory(Request $request)
	{
		$brands = Product::where('category_id', $request->category)
		->with('brand:id,name')
		->select('category_id','brand_id')
		->distinct()
		->get();
		// $brands = Product::where('category', $request->category)
		// 	->with('brand')
		// 	->select('brand')
		// 	->distinct()
		// 	->orderBy('brand', 'asc')
		// 	->get();

		return $this->successResponse($brands, 'Ok.');
	}

	public function getProductsByCategory(Request $request)
	{
		// $request->validate(['category' => 'required']);

		$products = Product::active()
			->when($request->mode === 'pascabayar', fn($q) => $q->where('type', 'pascabayar'))
			->when($request->brand, fn($q) => $q->where('brand_id', $request->brand))
			->when(!$request->brand, fn($q) => $q->where('category_id', $request->category))
			->ignoreCheck()
			->orderBy('price', 'asc')
			->orderBy('product_name', 'asc')
			->get(['id','buyer_sku_code', 'product_name', 'price', 'selling_price']);

		return $this->successResponse($products, 'Ok.');
	}

	public function search(Request $request)
	{
		$search = $request->search;

		$products = Product::where(DB::raw("LOWER(product_name)"), 'LIKE', "%$search%")
			->orWhere(DB::raw("LOWER(buyer_sku_code)"), 'LIKE', "%$search%")
			->orderBy('category_id', 'asc')
			->limit(20) // Batasi hasil agar ringan
			->get();

		$response = [];
		foreach($products as $product){
			// $response[] = [
			// 	"id" => $product->id,
			// 	"text" => $product->product_name . " (" . $product->buyer_sku_code . ")"
			// ];

			$currentCategory = $product->category ? $product->category->name : 'Tanpa Kategori';

			$response[] = [
				"id" => $product->id,
				"text" => $product->product_name, // Text utama
				"sku" => $product->buyer_sku_code,
				"category_text" => $currentCategory // Info tambahan untuk UI
			];
		}

		return $this->successResponse($response, 'Ok.');
	}
}
