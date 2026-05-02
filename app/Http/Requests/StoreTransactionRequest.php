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
			'category_id' => ['required', 'string'],

			// Wajib hanya jika category = games
			'user_id' => ['required_if:category_id,games', 'string'],
			'server_id' => ['required_if:category_id,games', 'string'],

			'product_code' => ['required', 'string', 'max:50'],

			// Wajib jika BUKAN games
			'target' => [
				'exclude_if:category_id,6',
				// 'required_unless:category_id,games',
				'required',
				'string',
				'max:20'
			],

			// 'category_id'  => ['required', 'string'],
			// 'user_id'      => ['required', 'string'],
			// 'product_code' => ['required', 'string', 'max:50'],
			// 'target'       => ['required', 'string', 'max:20'], // Max 20 agar tidak terlalu panjang

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
			'required_if' => ':attribute harus diisi!',
			'string' => ':attribute harus diisi!',
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
			'category_id'  => 'Kategori',
			'user_id'      => 'User ID',
			'server_id'    => 'Server ID',
			'product_code' => 'Produk',
			'target'       => 'Nomor Tujuan',
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
