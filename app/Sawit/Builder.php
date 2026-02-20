<?php

namespace App\Sawit;

use Illuminate\Support\Collection;

/**
 * Query Builder untuk SawitDB
 *
 * Menyimpan state query (where, order, limit) dan
 * bertanggung jawab membangun serta mengeksekusi SQL.
 */
class Builder
{
	protected string $table;

	protected array $wheres = [];
	protected ?string $orderBy = null;
	protected ?int $limit = null;

	/**
     * @param string $table Nama tabel yang akan di-query
     */
	public function __construct(string $table)
	{
		$this->table = $table;
	}

	/**
     * Support:
     *  - where('id', 1)
     *  - where('id', '>', 1)
     */
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

	/**
     * Eksekusi query dan return hasil sebagai Collection
     */
	public function get(array $columns = ['*']): Collection
	{
		$cols = implode(', ', $columns);
		$sql = "PANEN {$cols} DARI {$this->table}";

		if ($this->wheres) {
			$conditions = array_map(fn ($w) =>
				"{$w[0]} {$w[1]} " . SawitDB::value($w[2]),
				$this->wheres
			);
			$sql .= " DIMANA " . implode(' AND ', $conditions);
		}

		if ($this->orderBy)
			$sql .= " ORDER BY {$this->orderBy}";

		if ($this->limit)
			$sql .= " LIMIT {$this->limit}";

		return collect(SawitDB::query($sql));
	}

	/**
     * Ambil satu record pertama dari hasil query
     */
	public function first(array $columns = ['*'])
	{
		return $this->limit(1)->get($columns)->first();
	}
}
// public function get(array $columns = ['*']): Collection
// {
// 	$cols = implode(', ', $columns);

// 	$sql = "PANEN {$cols} DARI " . static::$table;

// 	if ($this->wheres) {
// 		$conditions = array_map(fn ($w) =>
// 			"{$w[0]} {$w[1]} " . SawitDB::value($w[2]),
// 			$this->wheres
// 		);
// 		$sql .= " DIMANA " . implode(' AND ', $conditions);
// 	}

// 	if ($this->orderBy)
// 		$sql .= " ORDER BY " . $this->orderBy;

// 	if ($this->limit)
// 		$sql .= " LIMIT " . $this->limit;

// 	$rows = SawitDB::query($sql);

// 	return collect($rows)->map(function ($row) {
// 		$model = new static;
// 		$model->attributes = (array) $row;
// 		return $model;
// 	});
// }
