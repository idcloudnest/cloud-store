<?php

namespace App\Sawit;

use SawitDB as NativeSawitDB;

class SawitDB
{
	public static function query(string $sql)
	{
		return NativeSawitDB::query($sql);
	}

	public static function insert(string $table, array $data)
	{
		$columns = implode(', ', array_keys($data));
		$values  = implode(', ', array_map([self::class, 'value'], $data));

		return self::query("TANAM KE {$table} ({$columns}) NILAI ({$values})");
	}

	public static function value($v)
	{
		if (is_null($v)) return 'NULL';
		if (is_array($v)) return "'" . json_encode($v) . "'";
		if ($v instanceof \DateTimeInterface) return "'" . $v->format('c') . "'";
        if (is_bool($v)) return $v ? 'true' : 'false';
		if (is_numeric($v)) return $v;

		return "'" . addslashes($v) . "'";
	}
}
