<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Models
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
	use \App\Traits\ApiResponser;

	public function index(Request $request)
	{
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
