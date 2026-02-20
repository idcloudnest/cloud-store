<?php

namespace App\Services\SawitDB;

use App\Jobs\WriteSawitLogJob;

/**
 * Class SawitLog.
 */
class SawitLog
{
	public static function info(
		string $type,
		string $message,
		array $context = [],
		?string $refId = null
	): void {
		self::dispatch($type, $message, $context, 'info', $refId);
	}

	public static function error(
		string $type,
		string $message,
		array $context = [],
		?string $refId = null
	): void {
		self::dispatch($type, $message, $context, 'error', $refId);
	}

	public static function warning(
		string $type,
		string $message,
		array $context = [],
		?string $refId = null
	): void {
		self::dispatch($type, $message, $context, 'warning', $refId);
	}

	protected static function dispatch(
		string $type,
		string $message,
		array $context,
		string $level,
		?string $refId
	): void {
		try {
			WriteSawitLogJob::dispatch(
				type: $type,
				message: $message,
				context: $context,
				level: $level,
				refId: $refId,
			)
			->onQueue('logging')
			->delay(0);
		} catch (\Throwable $e) {
			// fallback — jangan ganggu PPOB
			logger()->{$level}('[SawitLog fallback] ' . $message, [
				'type' => $type,
				'context' => $context,
				'error' => $e->getMessage(),
			]);
		}
	}
}
