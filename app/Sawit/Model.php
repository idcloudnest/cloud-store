<?php

namespace App\Sawit;

/**
 * Base Model Sawit
 *
 * Bertindak sebagai:
 *  - Entry point statik (Log::where(), Log::first())
 *  - Representasi satu record hasil query
 */
abstract class Model implements \JsonSerializable
{
	protected static string $primaryKey = 'id';

	protected static string $table;
	protected array $attributes = [];

	/* ========== ENTRY POINT ========== */

	/**
     * Buat instance Builder untuk model ini
     */
	public static function query(): Builder
    {
        return new Builder(static::$table);
    }

	/**
     * Forward static call ke Builder
     *
     * Contoh:
     *  Log::where(...) → (new Builder)->where(...)
     */
	public static function __callStatic($method, $arguments)
	{
		return static::query()->$method(...$arguments);
	}

	public static function first(array $columns = ['*'])
	{
		return static::query()->first($columns);
	}

	public static function find($id, array $columns = ['*'])
	{
		return static::query()
			->where(static::$primaryKey, $id)
			->first($columns);
	}

	/* ========== ACCESSOR ========== */

	/**
     * Akses attribute sebagai properti
     */
	public function __get($key)
	{
		return $this->attributes[$key] ?? null;
	}

	public function toArray(): array
	{
		return $this->attributes;
	}

    /**
     * Support json_encode()
     */
	public function jsonSerialize(): mixed
	{
		return $this->attributes;
	}
}
