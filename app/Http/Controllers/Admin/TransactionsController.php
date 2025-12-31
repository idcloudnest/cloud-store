<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\QueryException; // Penting untuk menangkap error database
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTransactionRequest;

class TransactionsController extends Controller
{
	use \App\Traits\ApiResponser;

	public function index()
	{
		return view('admin.transactions.index');
	}

	public function form(Request $request)
	{
		$users = User::orderBy('name', 'asc')->get();

		$brand = Brand::category()->get();
		return view('admin.transactions.manual', ['brand' => $brand, 'users' => $users]);
	}

	public function store(StoreTransactionRequest $request)
	{
		// return $this->successResponse($request->all(), 'Transaksi berhasil dibuat');
		return $validatedData = $request->validated();

		try {
			$transaction = new Transaction;

			return $this->successResponse($transaction, 'Transaksi berhasil dibuat', 201);
		} catch (QueryException $e) {
			$payload = $request->except(['pin', 'password', 'pin_transaksi']);

			// Ambil kode error MySQL
			$errorCode = $e->errorInfo[1] ?? 0;

			// Cek Error 1062 (Duplicate Entry)
			if ($errorCode == 1062) {
				\Log::warning('RACE_CONDITION_TRANSAKSI', [
					'user_id' => $request->user_id ?? 'guest',
					'invoice_attempt' => $request->invoice ?? 'auto',
					'payload' => $payload,
				]);

				### KIRIM NOTIF KE TELEGRAM
				return $this->errorResponse('Gagal memproses ID unik (Race Condition). Silakan coba lagi.', 409);
			}

			\Log::error('DB_EXCEPTION_STORE_TRANSAKSI', [
				'message' => $e->getMessage(),
				'file'    => $e->getFile(),
				'line'    => $e->getLine(),
				'user_id' => $request->user_id ?? 'guest',
				'payload' => $payload,
			]);

			### KIRIM NOTIF KE TELEGRAM
			return $this->errorResponse('Terjadi kesalahan pada server database.', 500);
		} catch (\Exception $e) {
			\Log::error('GENERAL_EXCEPTION_STORE_TRANSAKSI', [
				'message' => $e->getMessage(),
				'trace'   => $e->getTraceAsString(), // Penting untuk debugging error umum
				'user_id' => $request->user_id ?? 'guest',
				'payload' => $request->except(['pin', 'password', 'pin_transaksi']),
			]);

			### KIRIM NOTIF KE TELEGRAM
			return $this->errorResponse('Internal Server Error.', 500);
		}
	}
}
