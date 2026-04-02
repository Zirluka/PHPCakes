<?php

/* 

	Class mysqli_helper
	Содержит в себе полезные функции для упрощения работы с mysqli.
	Например функция get_array() перебирает вывод mysqli_query() и возвращает
	готовый массив

	Все функции в mysqli_helper являются статичными, это значит, что их можно 
	использовать без необходимости создавать экземпляр класса
	
*/
class mysqli_helper
{
	public static function get_array($mysqli_res) {
		// Перебирает вывод mysqli_query() и возвращает массив
		$mysqli_array = [];
		while($row = mysqli_fetch_assoc($mysqli_res)) {
			$mysqli_array[] = $row;
		}
		return $mysqli_array;
	}

	public static function get_select_query($columns, $table, $condition, $sign, $expression) {
		// Собирает sql запрос SELECT
		$query = "SELECT $columns FROM $table WHERE $condition $sign $expression";
		return $query;
	}

	public static function get_insert_query($table, $columns, $values) {
		// Собирает sql запрос INSERT
		$query = "INSERT INTO $table ($columns) VALUES ($values)";
		return $query;
	}

	public static function get_update_query($table, $values, $condition, $sign, $expression) {
		// Собирает sql запрос UPDATE
		$query = "UPDATE `$table` SET $values WHERE $condition $sign $expression";
		return $query;
	}

	public static function add_limit($query, $start, $count) {
		// Дополняет sql запрос LIMIT'ом
		$query .= " LIMIT $start, $count";
		return $query;
	}
}
