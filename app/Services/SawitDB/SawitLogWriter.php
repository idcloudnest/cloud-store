<?php

namespace App\Services\SawitDB;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use SawitDB;

/**
 * Class SawitLogWriter.
 */
class SawitLogWriter
{

	// | Kategori    | Contoh                                  |
	// | ----------- | --------------------------------------- |
	// | TRANSACTION | request topup, response provider        |
	// | PROVIDER    | request/response Digiflazz, dll         |
	// | BALANCE     | perubahan saldo                         |
	// | SYSTEM      | error, exception                        |
	// | SECURITY    | invalid signature, request mencurigakan |

	public function write(
		string $type,
		string $message,
		array $context,
		string $level,
		string $refId
	): void
	{
		$now = now()->toIso8601String();
		$ctx = json_encode($context, JSON_UNESCAPED_UNICODE);

		$refId = $refId ?? Str::uuid()->toString();

		// \Log::debug(json_encode([
		// 	'created_at' => $now,
		// 	'level' => $level,
		// 	'type' => $type,
		// 	'ref_id' => $refId,
		// 	'message' => $message,
		// 	'context' => $ctx
		// ], JSON_PRETTY_PRINT));

		$store = SawitDB::query("TANAM KE logs (
				created_at,
				level,
				type,
				ref_id,
				message,
				context
			) BIBIT (
				'{$now}',
				'{$level}',
				'{$type}',
				'{$refId}',
				'{$this->escape($message)}',
				'{$this->escape($ctx)}'
			)
		");

		// SawitDB::query("LAHAN logs;
		// 	TANAM KE logs (
		// 		id AUTO,
		// 		type STRING,
		// 		ref_id STRING,
		// 		message STRING,
		// 		context JSON,
		// 		level STRING,
		// 		created_at DATETIME
		// 	)
		// ");

		// $store = SawitDB::query("
		// 	TANAM KE cloud_nest_logs
		// 	(time, level, context, provider, product, message, response_ms, error_type)
		// 	BIBIT (
		// 		'{$data['time']}',
		// 		'{$data['level']}',
		// 		'{$data['context']}',
		// 		'{$data['sku']}',
		// 		'{$data['product']}',
		// 		'{$data['message']}',
		// 		{$data['response_ms']},
		// 		'{$data['error_type']}'
		// 	)
		// ");

		// \Log::debug(json_encode($store, JSON_PRETTY_PRINT));
	}

	protected function escape(string $value): string
	{
		return str_replace("'", "''", $value);
	}
}
