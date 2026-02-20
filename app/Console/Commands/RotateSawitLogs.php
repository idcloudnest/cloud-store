<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SawitDB;

class RotateSawitLogs extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'sawit:rotate-logs';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Purge old logs from SawitDB';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$days = config('sawit.retention_days', 30);
		$threshold = now()->subDays($days)->toDateTimeString();

		SawitDB::query("
			CABUT DARI logs
			DIMANA time < '{$threshold}'
		");

		$this->info("SawitDB logs older than {$days} days purged.");
	}
}
