<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException; // Penting untuk menangkap error database
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTransactionRequest;
use App\Services\Digiflazz\DigiflazzService;
use App\Services\Transaction\ManualTransactionService;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Services\TelegramService;

use App\Services\Provider\ProviderFactory;

// Models
use App\Models\BalanceHistory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;

class TransactionsController extends Controller
{
	use \App\Traits\ApiResponser;

	protected $manualTransactionService;
	protected $telegram;

	public function __construct(ManualTransactionService $manualTransactionService, TelegramService $telegram)
	{
		$this->manualTransactionService = $manualTransactionService;
		$this->telegram = $telegram;
	}

	public function show($id)
	{
		$transaction = Transaction::with('user:id,name')->with(['product' => fn($q) => $q->select('id','product_name','category_id')->with('category:id,name')])
		->where('invoice', $id)
		->first();
		// ->findOrFail($id);

		return $this->successResponse(
			$transaction,
			'Ok.'
		);
	}

	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = Transaction::select([
				'transactions.id',
				'product_name_snapshot',
				'invoice',
				'customer_no',
				'product_id',
				'total_amount',
				'transactions.created_at',
				'payment_status',
				'delivery_status'
			])->with(['product.category']);

			return DataTables::of($data)
				// Menambahkan kolom nomor urut otomatis (DT_RowIndex)
				->addIndexColumn()

				// Menambahkan kolom custom 'action' (tombol edit/hapus)
				->addColumn('action', function($row){
					$btn = '<div class="dropdown text-center">';

					// HAPUS: data-bs-display="static" (Ini biang keladinya)
					// GANTI DENGAN: data-bs-popper-config='{"strategy":"fixed"}'

					$btn .= '<button class="btn btn-light btn-sm action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config=\'{"strategy":"fixed"}\'>';
					$btn .= '<i class="fa-solid fa-ellipsis-vertical"></i>';
					$btn .= '</button>';

					$btn .= '<ul class="dropdown-menu dropdown-menu-end border-0 shadow">';
					// $btn .= '<li><a class="dropdown-item btn-detail" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-eye me-2 text-primary"></i> Details</a></li>';
					// $btn .= '<li><a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-print me-2 text-secondary"></i> Cetak</a></li>';
					// 1. Detail
					$btn .= '<li><a class="dropdown-item btn-detail" href="javascript:void(0)" data-id="'.$row->invoice.'"><i class="fa-solid fa-eye me-2 text-primary"></i> Details</a></li>';

					// 2. Cetak
					$btn .= '<li><a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-print me-2 text-secondary"></i> Cetak</a></li>';

					// Separator

					// 3. SEND ULANG JOBS (Baru)
					// Hanya tampilkan jika status belum sukses (opsional logic)
					if ($row->payment_status === 'paid' && $row->delivery_status === 'failed') {
						$btn .= '<li><hr class="dropdown-divider"></li>';
						$btn .= '<li><a class="dropdown-item btn-resend" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-paper-plane me-2 text-warning"></i> Kirim Ulang Job</a></li>';
					}
					$btn .= '</ul>';
					$btn .= '</div>';

					return $btn;
				})
				->addColumn('total_rupiah', function($row){
					// Ini akan memanggil Accessor 'totalRupiah' di model Anda
					return formatRupiah($row->total_amount);
				})
				// Format kolom tanggal (opsional, biar rapi)
				->editColumn('created_at', function($row){
					// return $row->created_at->format('Y-m-d H:i');
					return $row->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB';
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
				->addColumn('category', fn ($row) => $row?->product?->category?->name ?? '-')

				// Wajib memberitahu kolom mana yang mengandung HTML agar tidak di-escape
				->rawColumns(['action', 'payment_status', 'delivery_status', 'category'])

				// Finalisasi
				->make(true);
		}

		$trxCount = Transaction::count();
		$trxPendingCount = Transaction::where('delivery_status', 'pending')->count();
		$trxFailedCount = Transaction::where('delivery_status', 'failed')->count();
		// $categories = Product::categories()->get();
		$categories = Category::whereNotIn('id', [1,2])->get(['id', 'name']);
		$date = Carbon::now('Asia/Jakarta');
		$omzet = Transaction::where('delivery_status', 'success')->whereBetween('created_at', [
			$date->copy()->startOfMonth(), // 00:00:00 WIB (Otomatis jadi 17:00 Kemarin UTC)
			$date->copy()->endOfMonth()    // 23:59:59 WIB (Otomatis jadi 16:59 Hari Ini UTC)
		])
		->sum('total_amount');

		return view('admin.transactions.index', compact(
			'categories',
			'trxCount',
			'trxPendingCount',
			'trxFailedCount',
			'omzet',
		));
	}

	public function form(Request $request)
	{
		$users = User::orderBy('name', 'asc')->get();

		$categories = Category::all();
		return view('admin.transactions.manual', ['categories' => $categories, 'users' => $users]);
	}

	public function store(StoreTransactionRequest $request)
	{
		DB::beginTransaction();
		try {
			$user = User::where('id', $request->user_id)->lockForUpdate()->first();

			// $product = Product::where('buyer_sku_code', $request->product_code)->first();
			$product = Product::find($request->product_code);

			if (!$product) {
				DB::rollBack();
				return $this->errorResponse('Produk tidak ditemukan atau tidak aktif', 404);
			}

			$buyPrice = $product->price;
			$sellingPrice = $request->custom_price ?? $product->selling_price ?? $buyPrice;

			if ($user->role === 'admin')
				$sellingPrice = $buyPrice;

			$availableFunds = $user->balance + ($user->credit_limit ?? 0);
			if ($user->role !== 'admin' && $availableFunds < $sellingPrice) {
				DB::rollBack();
				return $this->errorResponse(
					"Saldo tidak mencukupi. (Saldo: " . formatRupiah($user->balance) . ")",
					400
				);
			}

			// 5. Generate Invoice & Record Transaksi Awal (Status: PENDING/QUEUED)
			$transaction = Transaction::create([
				'user_id'               => $user->id,
				'customer_no'           => $request->target,
				'zone_id'               => $request->zone_id,
				'product_id'            => $product->id,
				'product_name_snapshot' => $product->product_name,
				'sku_snapshot'          => $product->buyer_sku_code,
				'buy_price'             => $buyPrice,
				'amount'                => $sellingPrice,
				'total_amount'          => $sellingPrice,
				'payment_method'        => 'balance',
				'payment_status'        => 'paid',      // Sudah dianggap bayar karena saldo dipotong
				'delivery_status'       => 'pending',   // Status awal sebelum masuk queue
			]);

			// 6. Potong Saldo & Catat History
			$user->balance -= $sellingPrice;
			$user->save();

			BalanceHistory::create([
				'user_id'      => $user->id,
				'type'         => 'credit',
				'amount'       => $sellingPrice,
				'description'  => "Pembelian {$product->product_name} - #{$transaction->invoice}",
				'last_balance' => $user->balance
			]);

			// Kirim ID transaksi agar Job bisa mengambil data terbaru
			\App\Jobs\ProcessTransactionToProvider::dispatch($transaction->id)
				->onQueue('transactions') // <--- Nama queue bebas
				->afterCommit();

			DB::commit();

			return $this->successResponse(
				$transaction,
				'Transaksi sedang diproses dalam antrian.',
				201
			);

		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('TRANSACTION_CONTROLLER_ERROR', ['error' => $e->getMessage()]);
			return $this->errorResponse('Gagal memproses transaksi.', 500);
		}
	}

	public function pascaBayar(Request $request)
	{
		try {
			// 0709011699
			$user = User::where('id', $request->user_id)->lockForUpdate()->first();

			$product = Product::find($request->product_code);

			if (!$product) {
				return $this->errorResponse('Produk tidak ditemukan atau tidak aktif', 404);
			}

			$refId = 'INQ-' . time() . rand(100,999);

			$service = ProviderFactory::make('digiflazz');

			$response = $service->transaction(
				refId: $refId,
				skuCode: $product->buyer_sku_code,
				destination: $request->target,
				commands: 'inq-pasca' // <--- cek tagihan
			);
			// Log::debug(json_encode($response, JSON_PRETTY_PRINT));

			$data = $response['data'] ?? null;

			// RC 00 = Sukses Cek Tagihan
			if ($data && $data['rc'] === '00') {
				return $this->successResponse(
					[
						'customer_name' => $data['customer_name'] ?? '-',
						'customer_no'   => $data['customer_no'] ?? '-',
						'admin_fee'     => $data['admin'] ?? '-',
						'amount'        => $data['selling_price'] ?? '-', // Tagihan asli + admin dari provider
						'desc'          => $data['desc'] ?? '-',
						// Anda bisa simpan ref_id ini di session/db jika diperlukan untuk pay-pasca nanti
					],
					message: 'Tagihan ditemukan'
				);
			}

			return $this->errorResponse($data['message'] ?? 'Tagihan tidak ditemukan', 400);

		} catch (\Exception $e) {
			Log::error('CHECK BILL ERROR', ['message' => $th->getMessage()]);
			return $this->errorResponse('Internal server error!');
		}
	}
}
