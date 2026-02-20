<?php

namespace App\Sawit;

use Illuminate\Support\Collection;

abstract class Model implements \JsonSerializable
{
	protected static string $table;
	protected static array $fillable = [];
	protected static string $primaryKey = 'id';

	protected array $attributes = [];

	protected array $wheres = [];
	protected ?string $orderBy = null;
	protected ?int $limit = null;
	protected array $columns = ['*'];

	/* ---------- ENTRY POINT ---------- */

	public static function query(): static
	{
		return new static;
	}

	public static function __callStatic($method, $arguments)
	{
		return static::query()->$method(...$arguments);
	}

	public static function create(array $data)
	{
		$filtered = array_intersect_key(
			$data,
			array_flip(static::$fillable)
		);

		return SawitDB::insert(static::$table, $filtered);
	}

	// public static function get(array $columns = ['*'])
	// {
	// 	return static::query()->get($columns);
	// }

	public static function first(array $columns = ['*'])
	{
		return static::query()
			->limit(1)
			->get($columns)
			->first();
	}

	public static function find($id, array $columns = ['*'])
	{
		return static::query()
			->where(static::$primaryKey, $id)
			->limit(1)
			->get($columns)
			->first();
	}

	/* ================= CHAIN ================= */

	public function where(string $col, string $op, $val = null): static
	{
		if (func_num_args() === 2) {
			$val = $op;
			$op  = '=';
		}

		$this->wheres[] = [$col, $op, $val];
		return $this;
	}

	public function orderBy(string $col, string $dir = 'asc'): static
	{
		$this->orderBy = "{$col} {$dir}";
		return $this;
	}

	public function limit(int $n): static
	{
		$this->limit = $n;
		return $this;
	}

	/* ================= EXEC ================= */

	public function get(array $columns = ['*']): Collection
	{
		$cols = implode(', ', $columns);

		$sql = "PANEN {$cols} DARI " . static::$table;

		if ($this->wheres) {
			$conditions = array_map(fn ($w) =>
				"{$w[0]} {$w[1]} " . SawitDB::value($w[2]),
				$this->wheres
			);
			$sql .= " DIMANA " . implode(' AND ', $conditions);
		}

		if ($this->orderBy)
			$sql .= " ORDER BY " . $this->orderBy;

		if ($this->limit)
			$sql .= " LIMIT " . $this->limit;

		$rows = SawitDB::query($sql);

		return collect($rows)->map(function ($row) {
			$model = new static;
			$model->attributes = (array) $row;
			return $model;
		});
	}

	/* ================= ACCESSOR ================= */

	public function __get($key)
	{
		return $this->attributes[$key] ?? null;
	}

	public function toArray(): array
	{
		return $this->attributes;
	}

	public function jsonSerialize(): mixed
	{
		return $this->attributes;
	}
}
