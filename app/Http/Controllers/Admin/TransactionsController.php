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
use Illuminate\Support\Facades\Cache;

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
		$transaction = Transaction::with('user:id,name')->with(['product' => fn($q) => $q->select('id','product_name','category_id')->with('categories:id,name')])
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
				'delivery_status',
				'zone_id'
			// ])->with(['product.category'])->orderBy('transactions.created_at', 'desc');
			])->with(['product.categories:id,name'])->orderBy('transactions.created_at', 'desc');

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
				->addColumn('target', function($row){
					return strtolower(explode(',', $row->product->buyer_sku_code)[0] ?? '') == 'game' ? "{$row->customer_no} ({$row->zone_id})" : $row->customer_no;
				})
				// ->addColumn('category', fn ($row) => $row?->product?->category?->name ?? '-')
				// ->addColumn('category', fn ($row) => $row->product?->categories?->pluck('name')->implode(', ') ?? '-')
				->addColumn('category', function ($row) {
					if (!$row->product || $row->product->categories->isEmpty()) {
						return '-';
					}

					return $row->product->categories->map(function ($cat) {
						$color = ['info', 'primary', 'warning', 'danger'];
						$color = $color[array_rand($color)];
						return "<span class='badge bg-{$color} bg-opacity-10 text-{$color}' style='font-size: 0.7rem;'>"
							. e($cat->name) .
						'</span>';
					})->implode(' ');
				})

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

		$categories = Category::whereNull('parent_id')->get();
		return view('admin.transactions.manual', ['categories' => $categories, 'users' => $users]);
	}

	public function store(StoreTransactionRequest $request)
	{
		if ($request->transaction_type === 'pascabayar') {
			return $this->storePascabayar($request);
		}

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

			if ($user && $user->role === 'admin')
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
				'customer_name'         => $request->customer_name ?? null,
				'customer_no'           => $request->category == 'games' ? $request->game_user_id : $request->target,
				'zone_id'               => $request->game_server_id,
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

	private function storePascabayar(Request $request)
	{
		DB::beginTransaction();

		try {
			$refId = $request->inquiry_ref_id;
			$cacheKey = "pasca_inquiry:{$request->user_id}:{$refId}";
			$inquiry = Cache::get($cacheKey);

			if (!$inquiry) {
				DB::rollBack();

				return $this->errorResponse(
					'Sesi cek tagihan sudah expired. Silahkan cek tagihan ulang.',
					400
				);
			}

			if (
				(int) $inquiry['product_id'] !== (int) $request->product_code ||
				trim($inquiry['customer_no']) !== trim($request->target)
				) {
					DB::rollBack();

					return $this->errorResponse(
						'Data pembayaran tidak sama dengan hasil cek tagihan.',
						400
					);
				}

				$user = User::where('id', $request->user_id)->lockForUpdate()->first();
				$product = Product::find($request->product_code);

				if (!$user || !$product) {
					DB::rollBack();

					return $this->errorResponse('User atau produk tidak ditemukan.', 404);
				}

				$totalPay = (float) $inquiry['total_pay'];
				$availableFunds = $user->balance + ($user->credit_limit ?? 0);

				if ($user->role !== 'admin' && $availableFunds < $totalPay) {
					DB::rollBack();

					return $this->errorResponse(
						"Saldo tidak mencukupi. Saldo: " . formatRupiah($user->balance),
						400
					);
				}

				$service = ProviderFactory::make('digiflazz');

				$response = $service->transaction(
					refId: $refId,
					skuCode: $product->buyer_sku_code,
					destination: $request->target,
					commands: 'pay-pasca'
				);

				$data = $response['data'] ?? [];
				$rc = (string) ($data['rc'] ?? '');

				if ($rc !== '00') {
					DB::rollBack();

					return $this->errorResponse(
						$data['message'] ?? 'Pembayaran tagihan gagal.',
						400
					);
				}

				$providerStatus = strtolower($data['status'] ?? 'success');

				$deliveryStatus = str_contains($providerStatus, 'pending') ||
				str_contains($providerStatus, 'proses') ||
				str_contains($providerStatus, 'process')
				? 'processing'
				: 'success';

				$transaction = Transaction::create([
					'type' => 'pascabayar',
					'invoice' => Transaction::generateUniqueInvoice(),
					'ref_id' => $refId,

					'user_id' => $user->id,
					'customer_name' => $inquiry['customer_name'] ?? null,
					'customer_no' => $request->target,
					'zone_id' => null,

					'product_id' => $product->id,
					'product_name_snapshot' => $product->product_name,
					'sku_snapshot' => $product->buyer_sku_code,

					'buy_price' => $product->price,
					'amount' => $inquiry['amount'],
					'admin_fee' => $inquiry['admin_fee'],
					'total_amount' => $totalPay,

					'payment_method' => 'balance',
					'payment_status' => 'paid',
					'delivery_status' => $deliveryStatus,

					'sn' => $data['sn'] ?? null,
					'provider_message' => $data['message'] ?? null,
				]);

				$user->balance -= $totalPay;
				$user->save();

				BalanceHistory::create([
					'user_id' => $user->id,
					'type' => 'credit',
					'amount' => $totalPay,
					'description' => "Pembayaran tagihan {$product->product_name} - #{$transaction->invoice}",
					'last_balance' => $user->balance,
				]);

				Cache::forget($cacheKey);

				DB::commit();

				return $this->successResponse(
					$transaction,
					'Pembayaran tagihan berhasil diproses.',
					201
				);

			} catch (\Throwable $e) {
				DB::rollBack();

				Log::error('PAY_PASCA_ERROR', [
					'message' => $e->getMessage(),
					'request' => $request->all(),
				]);

				return $this->errorResponse('Gagal memproses pembayaran tagihan.', 500);
			}
		}

	public function pascaBayar(Request $request)
	{
		$request->validate([
			'user_id' => ['required', 'exists:users,id'],
			'product_code' => ['required', 'exists:products,id'],
			'target' => ['required', 'string', 'max:30'],
		]);

		try {
			$user = User::findOrFail($request->user_id);
			$product = Product::findOrFail($request->product_code);

			$refId = 'PASCA-' . now('Asia/Jakarta')->format('ymdHis') . '-' . random_int(100, 999);

			$service = ProviderFactory::make('digiflazz');

			$response = $service->transaction(
				refId: $refId,
				skuCode: $product->buyer_sku_code,
				destination: $request->target,
				commands: 'inq-pasca'
			);

			$data = $response['data'] ?? [];

			if (($data['rc'] ?? null) !== '00') {
				return $this->errorResponse($data['message'] ?? 'Tagihan tidak ditemukan', 400);
			}

			$adminFee = (int) ($data['admin'] ?? $data['admin_fee'] ?? 0);
			$totalPay = (int) ($data['selling_price'] ?? $data['total'] ?? $data['amount'] ?? 0);
			$amount = (int) ($data['price'] ?? $data['amount'] ?? max($totalPay - $adminFee, 0));

			if ($totalPay <= 0) {
				$totalPay = $amount + $adminFee;
			}

			$desc = $data['desc'] ?? '-';

			if (is_array($desc)) {
				$desc = collect($desc)
				->map(function ($value, $key) {
					if (is_array($value)) {
						$value = json_encode($value);
					}

					return "{$key}: {$value}";
				})
				->implode(' | ');
			}

			$payload = [
				'ref_id' => $refId,
				'user_id' => $user->id,
				'product_id' => $product->id,
				'sku' => $product->buyer_sku_code,
				'customer_no' => $request->target,
				'customer_name' => $data['customer_name'] ?? '-',
				'amount' => $amount,
				'admin_fee' => $adminFee,
				'total_pay' => $totalPay,
				'desc' => $desc,
				'raw' => $data,
			];

			Cache::put(
				"pasca_inquiry:{$user->id}:{$refId}",
				$payload,
				now('Asia/Jakarta')->endOfDay()
			);

			return $this->successResponse([
				'ref_id' => $refId,
				'customer_name' => $payload['customer_name'],
				'customer_no' => $payload['customer_no'],
				'admin_fee' => $payload['admin_fee'],
				'amount' => $payload['amount'],
				'total_pay' => $payload['total_pay'],
				'desc' => $payload['desc'],
			], 'Tagihan ditemukan');

		} catch (\Throwable $e) {
			Log::error('CHECK_BILL_ERROR', [
				'message' => $e->getMessage(),
				'request' => $request->all(),
			]);

			return $this->errorResponse('Gagal mengecek tagihan.', 500);
		}
	}

	// public function pascaBayar(Request $request)
	// {
	// 	try {
	// 		// 0709011699
	// 		$user = User::where('id', $request->user_id)->lockForUpdate()->first();

	// 		$product = Product::find($request->product_code);

	// 		if (!$product) {
	// 			return $this->errorResponse('Produk tidak ditemukan atau tidak aktif', 404);
	// 		}

	// 		$refId = 'INQ-' . time() . rand(100,999);

	// 		$service = ProviderFactory::make('digiflazz');

	// 		$response = $service->transaction(
	// 			refId: $refId,
	// 			skuCode: $product->buyer_sku_code,
	// 			destination: $request->target,
	// 			commands: 'inq-pasca' // <--- cek tagihan
	// 		);
	// 		// Log::debug(json_encode($response, JSON_PRETTY_PRINT));

	// 		$data = $response['data'] ?? null;

	// 		// RC 00 = Sukses Cek Tagihan
	// 		if ($data && $data['rc'] === '00') {
	// 			return $this->successResponse(
	// 				[
	// 					'customer_name' => $data['customer_name'] ?? '-',
	// 					'customer_no'   => $data['customer_no'] ?? '-',
	// 					'admin_fee'     => $data['admin'] ?? '-',
	// 					'amount'        => $data['selling_price'] ?? '-', // Tagihan asli + admin dari provider
	// 					'desc'          => $data['desc'] ?? '-',
	// 					// Anda bisa simpan ref_id ini di session/db jika diperlukan untuk pay-pasca nanti
	// 				],
	// 				message: 'Tagihan ditemukan'
	// 			);
	// 		}

	// 		return $this->errorResponse($data['message'] ?? 'Tagihan tidak ditemukan', 400);

	// 	} catch (\Exception $e) {
	// 		Log::error('CHECK BILL ERROR', ['message' => $th->getMessage()]);
	// 		return $this->errorResponse('Internal server error!');
	// 	}
	// }
}
