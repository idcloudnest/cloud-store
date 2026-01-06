<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

use App\Models\Brand;
use App\Models\Product;

class BrandsController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = Brand::getCategories()->withCount('products');

			return DataTables::of($data)
				// Menambahkan kolom nomor urut otomatis (DT_RowIndex)
				->addIndexColumn()
				->addColumn('provider_name', function($row) {
					// $row adalah object Brand.
					// Kita ambil semua products -> ambil provider -> ambil nama
					// Gunakan unique() jaga-jaga jika ada banyak produk dari provider sama

					$providers = $row->products->map(function($product) {
						return $product->provider->name ?? '-';
					})->unique()->implode(', '); // Gabung pakai koma jika lebih dari 1

					return $providers ?: '-'; // Return '-' jika kosong
				})
				->editColumn('category_list', function($row){
					// strtoupper(implode(' | ', $row->category_list))

					// 1. Ambil list kategori unik
					// $categories = $row->products->pluck('category')->unique();
					$categories = $row->category_list;

					$badges = [];

					foreach ($categories as $cat) {
						// 2. Tentukan warna berdasarkan nama kategori
						$color = match (strtolower($cat)) {
							'games'          => 'primary',   // Biru
							'pulsa'          => 'danger',    // Merah
							'e-money'        => 'success',   // Hijau
							'masa aktif'     => 'warning',   // Kuning
							'pln'            => 'info',      // Biru Muda
							'data'           => 'dark',      // Hitam
							'streaming'      => 'secondary', // Abu-abu
							default          => 'secondary'  // Default
						};

						// 3. Buat HTML Badge (Bootstrap 5)
						// 'me-1' untuk jarak antar badge
						$badges[] = '<span class="badge bg-'.$color.' me-1">'.strtoupper($cat).'</span>';
					}

					// Gabungkan semua badge jadi satu string
					return implode('', $badges);
				})
				->editColumn('products_count', fn($row) => "$row->products_count Produk")
				->addColumn('action', function($row) {
					$btn = '<div class="dropdown">';
					$btn .= '<button class="btn btn-light btn-sm action-btn" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>';
					$btn .= '<ul class="dropdown-menu dropdown-menu-end border-0 shadow">';
					$btn .= '<li><a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit Brand</a></li>';
					$btn .= '<li><a class="dropdown-item" href="#"><i class="fa-solid fa-list me-2 text-info"></i> Lihat Produk</a></li>';
					$btn .= '<li><hr class="dropdown-divider"></li>';
					$btn .= '<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-trash me-2"></i> Hapus</a></li>';
					$btn .= '</ul>';
					$btn .= '</div>';
					return $btn;
				})
				->editColumn('status', function($row) {
					// Cek status (1 = Aktif, 0 = Nonaktif)
					if ($row->status == 1) {
						return '<span class="badge bg-success"><i class="fa-solid fa-check-circle me-1"></i> AKTIF</span>';
					} else {
						return '<span class="badge bg-danger"><i class="fa-solid fa-ban me-1"></i> NONAKTIF</span>';
					}
				})
				// // Wajib memberitahu kolom mana yang mengandung HTML agar tidak di-escape
				->rawColumns(['action', 'category_list', 'status'])

				// Menambahkan kolom custom 'action' (tombol edit/hapus)
				// ->addColumn('action', function($row){
				// 	$btn = '<div class="dropdown text-center">';

				// 	// HAPUS: data-bs-display="static" (Ini biang keladinya)
				// 	// GANTI DENGAN: data-bs-popper-config='{"strategy":"fixed"}'

				// 	$btn .= '<button class="btn btn-light btn-sm action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config=\'{"strategy":"fixed"}\'>';
				// 	$btn .= '<i class="fa-solid fa-ellipsis-vertical"></i>';
				// 	$btn .= '</button>';

				// 	$btn .= '<ul class="dropdown-menu dropdown-menu-end border-0 shadow">';
				// 	// $btn .= '<li><a class="dropdown-item btn-detail" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-eye me-2 text-primary"></i> Details</a></li>';
				// 	// $btn .= '<li><a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-print me-2 text-secondary"></i> Cetak</a></li>';
				// 	// 1. Detail
				// 	$btn .= '<li><a class="dropdown-item btn-detail" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-eye me-2 text-primary"></i> Details</a></li>';

				// 	// 2. Cetak
				// 	$btn .= '<li><a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-print me-2 text-secondary"></i> Cetak</a></li>';

				// 	// Separator

				// 	// 3. SEND ULANG JOBS (Baru)
				// 	// Hanya tampilkan jika status belum sukses (opsional logic)
				// 	if ($row->payment_status === 'paid' && $row->delivery_status !== 'success') {
				// 		$btn .= '<li><hr class="dropdown-divider"></li>';
				// 		$btn .= '<li><a class="dropdown-item btn-resend" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-paper-plane me-2 text-warning"></i> Kirim Ulang Job</a></li>';
				// 	}
				// 	$btn .= '</ul>';
				// 	$btn .= '</div>';

				// 	return $btn;
				// })
				// ->addColumn('total_rupiah', function($row){
				// 	// Ini akan memanggil Accessor 'totalRupiah' di model Anda
				// 	return formatRupiah($row->total_amount);
				// })
				// // Format kolom tanggal (opsional, biar rapi)
				// ->editColumn('created_at', function($row){
				// 	// return $row->created_at->format('Y-m-d H:i');
				// 	return $row->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB';
				// })
				// ->editColumn('payment_status', function($row){
				// 	$status = $row->payment_status;

				// 	$color = 'secondary'; // Default warna abu-abu
				// 	switch ($status) {
				// 		case 'paid':
				// 			$color = 'success'; // Hijau
				// 			break;
				// 		case 'unpaid':
				// 			$color = 'warning'; // Kuning
				// 			break;
				// 		case 'expired':
				// 		case 'failed':
				// 			$color = 'danger';  // Merah
				// 			break;
				// 		case 'refunded':
				// 			$color = 'info';    // Biru muda
				// 			break;
				// 	}

				// 	// Return HTML Badge
				// 	return '<span class="badge bg-'.$color.' fw-normal px-2 py-1">'.strtoupper($status).'</span>';
				// 	// 'unpaid','paid','expired','failed','refunded'
				// 	return $row->payment_status;
				// })
				// ->editColumn('delivery_status', function($row){
				// 	// 'pending','processing','success','failed'
				// 	// return $row->delivery_status;
				// 	$status = $row->delivery_status;

				// 	$color = 'secondary';
				// 	switch ($status) {
				// 		case 'success':
				// 			$color = 'success'; // Hijau
				// 			break;
				// 		case 'pending':
				// 			$color = 'warning'; // Kuning
				// 			break;
				// 		case 'processing':
				// 			$color = 'primary'; // Biru
				// 			break;
				// 		case 'failed':
				// 			$color = 'danger';  // Merah
				// 			break;
				// 	}

				// 	// Return HTML Badge dengan sedikit margin agar rapi
				// 	return '<span class="badge bg-'.$color.' fw-normal px-2 py-1">'.strtoupper($status).'</span>';
				// })


				// Finalisasi
				->make(true);
		}

		$brandCount = Brand::count();
		$gameCount = Product::where('category', 'games')->count(DB::raw('DISTINCT brand'));
		$operatorCount = Product::whereIn('category', ['pulsa','masa aktif','data'])->count(DB::raw('DISTINCT brand'));

		return view('admin.product.brands.index', compact('brandCount', 'gameCount', 'operatorCount'));
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
