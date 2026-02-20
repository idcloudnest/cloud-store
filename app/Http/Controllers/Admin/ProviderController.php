<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProviderRequest;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Provider\ProviderFactory;

class ProviderController extends Controller
{
	use \App\Traits\ApiResponser;

	public function index()
	{
		$providers = Provider::all();

		return view('admin.providers.index', compact('providers'));
	}

	// public function store(Request $request)
	public function store(StoreProviderRequest $request)
	{
		DB::beginTransaction();
		try {
			$provider = Provider::updateOrCreate(
				['id' => $request->id],
				[
					'code'          => $request->code,
					'name'          => $request->name,
					'base_url'      => $request->base_url,
					'mode'          => $request->mode,
					'api_username'  => $request->api_username,
					'api_key'       => $request->api_key,
					'secret_key'    => $request->secret_key,
				]
			);

			DB::commit();

			return $this->successResponse(message: 'Provider berhasil disimpan');
		} catch (\Throwable $th) {
			DB::rollback();

			Log::error("Error Store Provider: " . $th->getMessage());

			return $this->errorResponse('Internal server error', 500);
		}
	}

	public function toggleStatus(Request $request)
	{
		$provider = Provider::findOrFail($request->id);
		$provider->is_active = !$provider->is_active;
		$provider->save();

		return $this->successResponse(['last_update' => $provider->last_update], message: 'Status provider berhasil diubah.');
	}

	public function checkBalance(Request $request)
	{
		$provider = Provider::findOrFail($request->id);

		DB::beginTransaction();
		try {
			$service = ProviderFactory::make($provider->code);

			$response = $service->checkBalance();

			if (!isset($response['data']))
				return $this->errorResponse('Gagal terhubung ke provider', 500);

			$balance = $response['data']['balance'] ?? $response['data']['deposit'] ?? 0;

			// $saldoBaru = rand(100000, 5000000);

			$provider->balance = $balance;
			$provider->save();

			DB::commit();

			return $this->successResponse([
				'balance' => $provider->balance_rupiah,
				'last_update' => $provider->last_update,
			], message: 'Status provider berhasil diubah.');

		} catch (\Exception $e) {
			DB::rollback();

			Log::error("Error CheckBalance Provider: " . $th->getMessage());

			return $this->errorResponse('Internal server error', 500);
		}
	}
}
