<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Transaction;
use App\Models\User;
use App\Models\BalanceHistory;
use App\Models\Product;
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
		// Load relasi user dan product agar bisa ditampilkan namanya
		$transaction = Transaction::with(['user', 'product'])->findOrFail($id);

		return $this->successResponse(
			$transaction,
			'Ok.'
		);
	}

	public function resendJob(Request $request)
	{
		$transaction = Transaction::where([
			['id', '=', $request->id],
			['payment_status', '=', 'paid'],
			['delivery_status', '!=', 'success'],
		])->first();

		if (!$transaction) {
			return $this->errorResponse('Transaksi tidak ditemukan', 404);
		}

		// Reset status agar tidak double process (Opsional, tergantung logic Anda)
		// $transaction->update(['delivery_status' => 'pending']);

		// Dispatch ulang ke Queue yang benar (Gunakan nama queue statis yg sudah kita bahas)
		\App\Jobs\ProcessTransactionToProvider::dispatch($transaction->id)
			->onQueue('transactions')
			->afterCommit();

		return $this->successResponse(message: 'Transaksi berhasil dikirim ulang ke antrian!');
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
			])->with('product:id,category');

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
					$btn .= '<li><a class="dropdown-item btn-detail" href="javascript:void(0)" data-id="'.$row->id.'"><i class="fa-solid fa-eye me-2 text-primary"></i> Details</a></li>';

					// 2. Cetak
					$btn .= '<li><a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-print me-2 text-secondary"></i> Cetak</a></li>';

					// Separator

					// 3. SEND ULANG JOBS (Baru)
					// Hanya tampilkan jika status belum sukses (opsional logic)
					if ($row->payment_status === 'paid' && $row->delivery_status !== 'success') {
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

				// Wajib memberitahu kolom mana yang mengandung HTML agar tidak di-escape
				->rawColumns(['action', 'payment_status', 'delivery_status'])

				// Finalisasi
				->make(true);
		}

		$trxCount = Transaction::count();
		$trxPendingCount = Transaction::where('delivery_status', 'pending')->count();
		$trxFailedCount = Transaction::where('delivery_status', 'failed')->count();
		$categories = Product::categories()->get();
		$date = Carbon::now('Asia/Jakarta');
		$omzet = Transaction::where('delivery_status', 'success')->whereBetween('created_at', [
			$date->copy()->startOfDay(), // 00:00:00 WIB (Otomatis jadi 17:00 Kemarin UTC)
			$date->copy()->endOfDay()    // 23:59:59 WIB (Otomatis jadi 16:59 Hari Ini UTC)
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

		$categories = Product::categories()->get();
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

			// $reason = "Transaksi testing berhasil dibuat";
			// $this->telegram->sendTransactionError($transaction, $reason);

			// $customerNo = trim($transaction->customer_no . $transaction->zone_id);

			// // Hit API Provider
			// $service = ProviderFactory::make('digiflazz');

			// $response = $service->transaction(
			// 	refId: $transaction->invoice,
			// 	skuCode: $transaction->sku_snapshot,
			// 	destination: $customerNo
			// );

			// $apiData = $response['data'] ?? null;

			// // KASUS 1: Tidak ada respon dari API / Error Koneksi
			// if (empty($apiData)) {
			// 	$reason = 'No Response from Provider (Empty Data)';
			// 	$manualTransactionService->processRefund($transaction, $transaction->total_amount, $reason);
			// 	DB::commit();
			// 	$this->telegram->sendTransactionError($transaction, $reason);
			// 	return;
			// }

			// // Cek RC (00 = Sukses, 03 = Pending)
			// if (in_array($apiData['rc'], ['00', '03'])) {
			// 	$status = ($apiData['rc'] === '00') ? 'success' : 'processing';

			// 	$transaction->update([
			// 		'delivery_status'  => $status,
			// 		'sn'               => $apiData['sn'] ?? null,
			// 		'provider_message' => $apiData['message'] ?? 'Diproses Provider',
			// 	]);
			// }
			// // Transaksi Ditolak Provider (Gagal Langsung)
			// else {
			// 	$reason = $apiData['message'] ?? 'Gagal dari Provider (RC Unknown)';
			// 	$manualTransactionService->processRefund($transaction, $transaction->total_amount, $reason);
			// 	$this->telegram->sendTransactionError($transaction, $reason);
			// }

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

	// public function store(StoreTransactionRequest $request)
	// // public function store(Request $request)
	// {
	// 	// return $this->successResponse($request->all(), 'Produk tidak ditemukan atau tidak aktif');

	// 	DB::beginTransaction();
	// 	try {
	// 		$user = User::where('id', $request->user_id)->lockForUpdate()->first();
	// 		// $product = Product::where('buyer_sku_code', $request->product_code)->first();
	// 		$product = Product::find($request->product_code);
	// 		// return $this->successResponse($product, 'Produk tidak ditemukan atau tidak aktif');

	// 		if (!$product) {
	// 			DB::rollBack();
	// 			return $this->errorResponse('Produk tidak ditemukan atau tidak aktif', 404);
	// 		}

	// 		$buyPrice = $product->price; // Harga Modal (Dari DB Product / Digiflazz)

	// 		// Logika Harga Jual:
	// 		// Jika admin input harga manual (custom_price), pakai itu.
	// 		// Jika tidak, pakai harga jual default produk (selling_price).
	// 		// Jika selling_price belum diset, sementara pakai modal (tidak untung).
	// 		$sellingPrice = $request->custom_price ?? $product->selling_price ?? $buyPrice; // Harga Modal (Dari DB Product / Digiflazz)

	// 		if ($user->role === 'admin')
	// 			$sellingPrice = $buyPrice;

	// 		// Hitung Dana Tersedia (Saldo Real + Batas Hutang)
	// 		// Jika credit_limit null, anggap 0
	// 		$availableFunds = $user->balance + ($user->credit_limit ?? 0);

	// 		// Pengecekan:
	// 		// Jika user BUKAN admin, maka harus mengikuti limit.
	// 		// (Opsional: Admin juga bisa dipaksa ikut limit demi keamanan)
	// 		if ($user->role !== 'admin') {
	// 			if ($availableFunds < $sellingPrice) {
	// 				DB::rollBack();
	// 				return $this->errorResponse(
	// 					"Saldo tidak mencukupi. (Saldo: " . formatRupiah($user->balance) .
	// 					", Limit Hutang: " . formatRupiah($user->credit_limit ?? 0) . ")",
	// 					400
	// 				);
	// 			}
	// 		}

	// 		$transaction = Transaction::create([
	// 			'user_id'               => $user->id,
	// 			'customer_no'           => $request->target,
	// 			'zone_id'               => $request->zone_id,
	// 			'product_id'            => $product->id,
	// 			'product_name_snapshot' => $product->product_name,
	// 			'sku_snapshot'          => $product->buyer_sku_code,
	// 			'buy_price'             => $buyPrice,
	// 			'amount'                => $sellingPrice,
	// 			'total_amount'          => $sellingPrice,
	// 			'payment_method'        => 'balance',
	// 			'payment_status'        => 'paid',
	// 			'delivery_status'       => 'processing',
	// 		]);

	// 		$user->balance -= $sellingPrice;
	// 		$user->save();

	// 		BalanceHistory::create([
	// 			'user_id'      => $user->id,
	// 			'type'         => 'credit', // Credit = Uang Keluar dari Dompet
	// 			'amount'       => $sellingPrice,
	// 			'description'  => "Pembelian {$product->product_name} - #{$transaction->invoice}",
	// 			'last_balance' => $user->balance
	// 		]);

	// 		// Gabungkan Nomor + Zone ID jika ada (Format Game: 12345678901234)
	// 		$customerNo = trim($transaction->customer_no . $transaction->zone_id);

	// 		$response = $this->digiflazz->transaction(
	// 			refId: $transaction->invoice,
	// 			buyerSkuCode: $transaction->sku_snapshot,
	// 			customerNo: $customerNo,
	// 			// maxPrice: 500
	// 		);

	// 		$apiData = $response['data'] ?? null;

	// 		if (empty($apiData)) {
	// 			$this->manualTransactionService->processRefund($transaction, $sellingPrice, 'No Response from Provider');

	// 			DB::commit();
	// 			return $this->errorResponse('Gagal menghubungi provider. Saldo dikembalikan.', 502);
	// 		}

	// 		// Cek RC (00 = Sukses, 03 = Pending)
	// 		if (in_array($apiData['rc'], ['00', '03'])) {
	// 			$transaction->update([
	// 				'delivery_status'  => $apiData['rc'] === '00' ? 'success' : 'processing',
	// 				'sn'               => $apiData['sn'] ?? null,
	// 				'provider_message' => $apiData['message'] ?? 'Transaksi diproses',
	// 			]);

	// 			DB::commit(); // Transaksi Final Sukses
	// 			return $this->successResponse(null, 'Transaksi berhasil diproses', 201);

	// 		} else {
	// 			$this->manualTransactionService->processRefund($transaction, $sellingPrice, $apiData['message']);

	// 			DB::commit();
	// 			return $this->errorResponse('Transaksi Gagal: ' . $apiData['message'], 400);
	// 		}
	// 	} catch (QueryException $e) {
	// 		$payload = $request->except(['pin', 'password', 'pin_transaksi']);

	// 		// Ambil kode error MySQL
	// 		$errorCode = $e->errorInfo[1] ?? 0;

	// 		// Cek Error 1062 (Duplicate Entry)
	// 		if ($errorCode == 1062) {
	// 			DB::rollBack();

	// 			Log::warning('RACE_CONDITION_TRANSAKSI', [
	// 				'user_id' => $request->user_id ?? 'guest',
	// 				'invoice_attempt' => $request->invoice ?? 'auto',
	// 				'payload' => $payload,
	// 			]);

	// 			### KIRIM NOTIF KE TELEGRAM
	// 			return $this->errorResponse('Gagal memproses ID unik (Race Condition). Silakan coba lagi.', 409);
	// 		}

	// 		DB::rollBack();

	// 		Log::error('DB_EXCEPTION_STORE_TRANSAKSI', [
	// 			'message' => $e->getMessage(),
	// 			'file'    => $e->getFile(),
	// 			'line'    => $e->getLine(),
	// 			'user_id' => $request->user_id ?? 'guest',
	// 			'payload' => $payload,
	// 		]);

	// 		### KIRIM NOTIF KE TELEGRAM
	// 		return $this->errorResponse('Terjadi kesalahan pada server database.', 500);
	// 	} catch (\Exception $e) {
	// 		DB::rollBack();

	// 		Log::error('GENERAL_EXCEPTION_STORE_TRANSAKSI', [
	// 			'message' => $e->getMessage(),
	// 			'trace'   => $e->getTraceAsString(), // Penting untuk debugging error umum
	// 			'user_id' => $request->user_id ?? 'guest',
	// 			'payload' => $request->except(['pin', 'password', 'pin_transaksi']),
	// 		]);

	// 		### KIRIM NOTIF KE TELEGRAM
	// 		return $this->errorResponse('Internal Server Error.', 500);
	// 	}
	// }
}
