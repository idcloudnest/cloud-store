<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SawitLog;

// Models
use App\Models\Category;
use App\Models\Product;
use SawitDB;
use App\Sawit\Models\Log as LogModel;

class CategoryController extends Controller
{
	use \App\Traits\ApiResponser;

	public function index(Request $request)
	{
		// \Log::debug('tes');
		// return 'ok';
		// return LogModel::get();
		// $log = LogModel::
		// // where('created_at', '2026-01-11T15:00:54+00:00')->
		// // get();
		// // orderBy('created_at', 'desc')->
		// first();
		// // find('2026-01-11T15:01:18+00:00');
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
		// 	message: "Testing error pertama",
		// 	context: $request->all(),
		// 	// refId: $request->product_id,
		// 	refId: 123,
		// );
		// return "ok";

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
		// // $sawit = SawitDB::query("GUSUR DARI logs DIMANA level = 'error'");
		// return $sawit;

		// $now = now()->toIso8601String();
		// SawitDB::query("TANAM KE logs (
		// 		created_at,
		// 		level,
		// 		type,
		// 		ref_id,
		// 		message,
		// 		context
		// 	) BIBIT (
		// 		'{$now}',
		// 		'error',
		// 		'system',
		// 		'123',
		// 		'pesan',
		// 		''
		// 	)
		// ");
		// return "stored";

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

		// return SawitDB::query("LIHAT LAHAN");
		if ($request->ajax()) {
			$data = Category::with('parent')->withCount('products');

			return DataTables::of($data)
				->addIndexColumn()
				->addColumn('parent_name', function($row){
					return $row->parent ? $row->parent->name : '-';
				})
				->editColumn('products_count', fn($row) => "$row->products_count Produk")
				->editColumn('status', fn($row) => $row->status ? '<span class="badge badge-soft-success">AKTIF</span>' : '<span class="badge badge-soft-danger">NON-AKTIF</span>')
				->addColumn('action', function($row){
					$btn = '<div class="dropdown text-end">';

					// HAPUS: data-bs-display="static" (Ini biang keladinya)
					// GANTI DENGAN: data-bs-popper-config='{"strategy":"fixed"}'

					$btn .= '<button class="btn btn-light btn-sm action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config=\'{"strategy":"fixed"}\'>';
					$btn .= '<i class="fa-solid fa-ellipsis-vertical"></i>';
					$btn .= '</button>';

					$btn .= '<ul class="dropdown-menu dropdown-menu-end border-0 shadow">';
					$btn .= '<li><a class="dropdown-item btn-detail btn-view-products" href="javascript:void(0)" data-id="'.$row->id.'" data-name="'.$row->name.'"><i class="fa-solid fa-eye me-2 text-primary"></i> Lihat Produk</a></li>';
					$btn .= '<li><a class="dropdown-item btn-detail btn-edit" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-pen-to-square me-2 text-secondary"></i> Edit</a></li>';
					$btn .= '</ul>';
					$btn .= '</div>';

					return $btn;
				})
				->rawColumns(['status', 'action'])
				->make(true);
		}

		// 2. Jika Request Biasa (Load Halaman)
		// Ambil list parent untuk dropdown di modal
		$parents = Category::get();
		return view('admin.product.categories.index', compact('parents'));
	}

	public function getProductsByCategory($id)
	{
		// Ambil produk yang category_id nya sesuai request
		$query = Product::where('category_id', $id)->latest();

		return DataTables::of($query)
			->addIndexColumn()
			->editColumn('price', function($row){
				return 'Rp ' . number_format($row->price, 0, ',', '.');
			})
			->editColumn('status', function($row){
				return $row->status
					? '<span class="badge bg-success">Aktif</span>'
					: '<span class="badge bg-danger">Non-Aktif</span>';
			})
			->rawColumns(['status'])
			->make(true);
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'parent_id' => 'nullable|exists:categories,id',
			'status' => 'required|boolean',
		]);


		DB::beginTransaction();
		try {
			$provider = Category::updateOrCreate(
				['id' => $request->category_id],
				[
					'parent_id' => $request->parent_id,
					'name' => $request->name,
					'slug' => Str::slug($request->name) . '-' . Str::random(5),
					'status' => $request->status,
					'sort_order' => $request->sort_order,
				]
			);

			DB::commit();

			return $this->successResponse(message: 'Category berhasil disimpan!');
		} catch (\Throwable $th) {
			DB::rollback();

			Log::error("Error Store Categories: " . $th->getMessage());

			return $this->errorResponse('Internal server error', 500);
		}
	}

	public function show($id)
	{
		$category = Category::find($id);

		return $this->successResponse(
			$category,
			'Ok.'
		);
	}

	public function update(Request $request, Category $category)
	{
		$category = Category::find($id);

		$request->validate([
			'name' => 'required|string|max:255',
			'parent_id' => 'nullable|exists:categories,id',
			'status' => 'required|boolean',
		]);

		if ($request->parent_id == $category->id) {
			return response()->json(['message' => 'Kategori tidak bisa menjadi parent dirinya sendiri.'], 422);
		}

		$category->update([
			'name' => $request->name,
			'slug' => Str::slug($request->name),
			'parent_id' => $request->parent_id,
			'status' => $request->status,
		]);

		return response()->json(['message' => 'Kategori berhasil diperbarui!']);
	}

	public function destroy($id)
	{
		Category::find($id)->delete();
		return response()->json(['message' => 'Kategori berhasil dihapus!']);
	}

	public function assignProducts(Request $request)
	{
		$request->validate([
			'category_id' => 'required|exists:categories,id',
			'product_ids' => 'required|array',
			'product_ids.*' => 'exists:products,id',
		]);

		// Product::where('category_id', $request->category_id)->whereNotIn('id', $request->product_ids)->update(['category_id' => null]);

		// // Update massal semua produk yang dipilih
		// Product::whereIn('id', $request->product_ids)->update(['category_id' => $request->category_id]);
		Product::whereIn('id', $request->product_ids)->update(['category_id' => $request->category_id]);

		return $this->successResponse(
			message: count($request->product_ids) . ' Produk berhasil dipindahkan ke kategori ini!'
		);
	}
}
