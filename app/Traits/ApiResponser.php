<?php

namespace App\Traits;

trait ApiResponser
{
	/**
	 * Return response JSON sukses standar.
	 * * @param mixed $data (Bisa Array, Collection, Model, Request, atau Resource)
	 */
	protected function successResponse(
		mixed $data,
		?string $message = null,
		int $code = 200
	)
	{
		return response()->json([
			'meta' => [
				'code' => $code,
				'status' => 'success',
				'message' => $message,
			],
			'data' => $data
		], $code);
	}

	protected function errorResponse(
		?string $message = null,
		int $code = 500
	)
	{
		return response()->json([
			'meta' => [
				'code' => $code,
				'status' => 'error',
				'message' => $message,
			],
			'data' => null
		], $code);
	}
}
