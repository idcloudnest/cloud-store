<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use SawitLog;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;


class BrandsController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = Brand::withCount('products');

			return DataTables::of($data)
				// Menambahkan kolom nomor urut otomatis (DT_RowIndex)
				->addIndexColumn()
				->addColumn('provider_name', function($row) {
					// $row adalah object Brand.
					// Kita ambil semua products -> ambil provider -> ambil nama
					// Gunakan unique() jaga-jaga jika ada banyak produk dari provider sama

					$providers = $row->products->map(function($product) {
						return $product->provider->name ?? '-';
					})->unique()->implode(', ');

					return $providers ?: '-';
				})
				->editColumn('products_count', fn($row) => "$row->products_count Produk")
				->addColumn('action', function($row) {
					$btn = '<div class="dropdown text-end">';
					$btn .= '<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown" data-bs-popper-config=\'{"strategy":"fixed"}\'><i class="fa-solid fa-ellipsis-vertical"></i></button>';
					$btn .= '<ul class="dropdown-menu dropdown-menu-end border-0 shadow">';
					$btn .= '<li><a class="dropdown-item btn-edit" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Brand</a></li>';
					$btn .= '<li><a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-eye me-2 text-info"></i> Lihat Produk</a></li>';
					$btn .= '<li><hr class="dropdown-divider"></li>';
					$btn .= '<li><a class="dropdown-item text-danger" href="javascript:void(0)"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>';
					$btn .= '</ul>';
					$btn .= '</div>';
					return $btn;
				})
				->editColumn('status', function($row) {
					if ($row->status == 1) {
						return '<span class="badge bg-success"><i class="fa-solid fa-check-circle me-1"></i> AKTIF</span>';
					} else {
						return '<span class="badge bg-danger"><i class="fa-solid fa-ban me-1"></i> NONAKTIF</span>';
					}
				})
				// Wajib memberitahu kolom mana yang mengandung HTML agar tidak di-escape
				->rawColumns(['action', 'category_list', 'status'])
				->make(true);
		}

		$brandCount = Brand::count();
		// $gameCount = Product::where('category', 'games')->count(DB::raw('DISTINCT brand'));
		$gameCount = Product::where('category_id', 6)->count();
		// $operatorCount = Product::whereIn('category', ['pulsa','masa aktif','data'])->count(DB::raw('DISTINCT brand'));
		$operatorCount = Product::where('category_id', 3)->count();
		$gangguanCount = Product::where('status', 0)->distinct()->count();

		return view('admin.product.brands.index', compact('brandCount', 'gameCount', 'operatorCount', 'gangguanCount'));
	}

	public function form(Request $request)
	{
		try {
			$id = $request->brand_id;
			$title = $id ? 'Edit Brand' : 'Tambah Brand';
			$brand = Brand::find($request->brand_id);
			$category = Category::whereNotIn('id', [1,2])->get(['id','name']);

			$content = view('admin.product.brands.form', compact('brand','title','category'))->render();
			return $this->successResponse(['content' => $content], 'Ok');
		} catch (\Throwable $e) {
			return $this->errorResponse($e->getMessage());
		}
	}

	public function store(Request $request)
	{
		$store = Brand::find($request->brand_id);

		DB::beginTransaction();

		try {
			$oldImage = $store?->image;
			// 📌 UPLOAD LOGO VIA SFTP (SSH)
			if ($request->hasFile('image')) {
				$file = $request->file('image');
				$fileName = date('His') . '-' . $file->getClientOriginalName();
				$date = now()->toDateString();

				$path = "{$this->domain}/brands/{$date}";
				$remotePath = "{$path}/{$fileName}";

				Storage::disk('sftp_assets')
					->putFileAs($path, $file, $fileName);

				// === COMPRESS IMAGE ===
				// $manager = new ImageManager(new Driver());

				// $image = $manager->read($file)
				// 	->scaleDown(width: 1024) // max width 1024px
				// 	->toJpeg(75);            // quality 75%

				// simpan sementara
				// $tempPath = storage_path("app/tmp_{$fileName}");
				// $image->save($tempPath);

				// upload ke SFTP
				// Storage::disk('sftp_assets')->put(
				// 	$remotePath,
				// 	fopen($tempPath, 'r+')
				// );

				// hapus temp file
				// @unlink($tempPath);

			}

			$store = Brand::updateOrCreate(
				['id' => $request->brand_id],
				[
					'name'   => $request->name,
					'category_id'   => $request->category_id,
					'slug'   => $request->slug,
					'color'  => $request->color,
					'status' => (bool) $request->status,
					'image'  => $remotePath ?? $oldImage,
				]
			);

			// hapus file lama SETELAH sukses
			if (
				isset($remotePath) &&
				$oldImage &&
				Storage::disk('sftp_assets')->exists($oldImage)
			) {
				Storage::disk('sftp_assets')->delete($oldImage);
			}

			DB::commit();

			return $this->successResponse($store, 'Data brand berhasil disimpan.');

		} catch (\Throwable $th) {
			DB::rollBack();

			SawitLog::error(
				type: 'SYSTEM',
				message: $th->getMessage(),
				context: $request->only('brand_id','name','status'),
				refId: $request->brand_id,
			);

			return $this->errorResponse($th->getMessage());
		}
		// $request->validate([
		// 	'name'  => 'required|string|max:255',
		// 	'slug'  => 'required|string|max:255|unique:brands,slug',
		// 	'color' => 'nullable|string',
		// 	'image' => 'nullable|image|max:2048',
		// 	'icon'  => 'nullable|image|max:1024',
		// 	'status'=> 'required|boolean',
		// ]);

		// $data = $request->only(['name', 'slug', 'color', 'status']);

		// if ($request->hasFile('image')) {
		// 	$data['image'] = $request->file('image')->store('brands', 'public');
		// }

		// if ($request->hasFile('icon')) {
		// 	$data['icon'] = $request->file('icon')->store('brands/icons', 'public');
		// }

		// $brand->update($data);

		// return response()->json([
		// 	'message' => 'Brand berhasil diperbarui'
		// ]);
	}

	public function data(Request $request)
	{
		// Simpan di cache selama 60 menit (3600 detik)
		$data = Cache::remember('brands_list', 3600, function () {
			return Brand::select('id', 'name', 'slug')->get();
		});

		return response()->json($data);
	}
}
