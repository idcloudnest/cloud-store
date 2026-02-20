<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateQueryHelper
{
	/**
	 * Generate UTC ISO-8601 range for WHERE DATE
	 *
	 * @param  string|\DateTimeInterface  $date   (Y-m-d or Carbon)
	 * @param  string|null               $tz     (default: app timezone)
	 * @return array{start:string,end:string}
	 */
	public static function whereDate($date, ?string $tz = 'Asia/Jakarta'): array
	{
		$tz = $tz ?? config('app.timezone', 'UTC');

		$day = $date instanceof \DateTimeInterface
			? Carbon::instance($date)
			: Carbon::parse($date, $tz);

		$startUtc = $day->clone()
			->startOfDay()
			->setTimezone('UTC')
			->toIso8601String();

		$endUtc = $day->clone()
			->endOfDay()
			->setTimezone('UTC')
			->toIso8601String();

		return [
			'start' => $startUtc,
			'end'   => $endUtc,
		];
	}

	/**
	 * WHERE date range (multi-day)
	 */
	public static function whereBetweenDates(
		$from,
		$to,
		?string $tz = 'Asia/Jakarta'
	): array {
		$tz = $tz ?? config('app.timezone', 'UTC');

		$startUtc = Carbon::parse($from, $tz)
			->startOfDay()
			->setTimezone('UTC')
			->toIso8601String();

		$endUtc = Carbon::parse($to, $tz)
			->endOfDay()
			->setTimezone('UTC')
			->toIso8601String();

		return [
			'start' => $startUtc,
			'end'   => $endUtc,
		];
	}

	public static function whereDateSql(
		string $column,
		$date,
		?string $tz = 'Asia/Jakarta'
	): string {
		$r = self::whereDate($date, $tz);
		return "{$column} >= '{$r['start']}' AND {$column} <= '{$r['end']}'";
	}
}
