<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProviderRequest extends FormRequest
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
			'name'     => ['required', 'string', 'min:3'],
			'code'     => ['required', 'string'],
			'api_key'  => ['required', 'string'],
			'mode'     => ['required', 'string'],
			'base_url' => ['required', 'string'],
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
			'max' => ':attribute maximal :max karakter!',
		];
	}

	/**
	 * Custom nama atribut agar pesan error lebih manusiawi.
	 */
	public function attributes(): array
	{
		return [
			'name'     => 'Nama',
			'code'     => 'Kode (Slug)',
			'api_key'  => 'API Key',
			'mode'     => 'Mode',
			'base_url' => 'Base URL',
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
