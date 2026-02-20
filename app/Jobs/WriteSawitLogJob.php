<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// Services
use App\Services\SawitDB\SawitLogWriter;

class WriteSawitLogJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public string $type;
	public string $message;
	public array $context;
	public string $level;
	public string $refId;

	public int $tries = 1;
	public int $timeout = 3;

	/**
	 * Create a new job instance.
	 */
	public function __construct(
		string $type,
		string $message,
		array $context = [],
		string $level = 'info',
		?string $refId = null
	)
	{
		$this->type = $type;
		$this->message = $message;
		$this->context = $context;
		$this->level = $level;
		$this->refId = $refId ?? (string) \Illuminate\Support\Str::uuid();
	}

	/**
	 * Execute the job.
	 */
	public function handle(SawitLogWriter $writer): void
	{
		try {
			$writer->write(
				type: $this->type,
				message: $this->message,
				context: $this->context,
				level: $this->level,
				refId: $this->refId,
			);
		} catch (\Throwable $e) {
			// jangan ganggu PPOB
			logger()->warning('SawitDB write failed', [
				'error' => $e->getMessage(),
				'file' => $e->getFile(),
			]);
		}
	}
}
