<?php

class mysqli_helper
{
	public static function get_array($mysqli_res) {
		$mysqli_array = [];
		while($row = mysqli_fetch_assoc($mysqli_res)) {
			$mysqli_array[] = $row;
		}
		return $mysqli_array;
	}

	public static function get_select_query($columns, $table, $condition, $sign, $expression) {
		$query = "SELECT $columns FROM $table WHERE $condition$sign$expression";
		return $query;
	}

	public static function get_insert_query($table, $columns, $values) {
		$query = "INSERT INTO $table ($columns) VALUES ($values)";
		return $query;
	}

	public static function get_update_query($token, $id) {
		$query = "UPDATE `users` SET `token`='$token' WHERE id=$id";
		return $query;
	}

	public static function add_limit($query, $start, $count) {
		$query .= " LIMIT $start, $count";
		return $query;
	}
}
