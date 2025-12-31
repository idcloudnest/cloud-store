<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTransactionRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'user_id'      => ['required', 'string'],
			'product_code' => ['required', 'string', 'max:50'],
			'target'       => ['required', 'string', 'max:20'], // Max 20 agar tidak terlalu panjang
			'amount'       => ['required', 'numeric', 'min:1'],

			// Opsional: Validasi PIN Transaksi jika ada
			// 'pin' => ['required', 'digits:6'],
		];
	}

	/**
	 * Custom message for validation
	 *
	 * @return array
	 */
	public function messages(): array
	{
		return [
			'required' => ':attribute harus diisi!',
			'min' => ':attribute minimal :min karakter!',
			'max' => ':attribute maximal :max digit!',
		];
	}

	/**
	 * Custom nama atribut agar pesan error lebih manusiawi.
	 */
	public function attributes(): array
	{
		return [
			'user_id'      => 'Pelanggan',
			'product_code' => 'Kode Produk',
			'target'       => 'Nomor Tujuan',
			'amount'       => 'Harga / Nominal',
		];
	}

	/**
	 * OVERRIDE: Agar format error sesuai pattern JSON Anda.
	 */
	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json([
			'meta' => [
				'code'    => 422,
				'status'  => 'error',
				'message' => 'Data yang dikirim tidak valid.',
			],
			'data' => $validator->errors(), // Tampilkan list error detail
		], 422));
	}
}
