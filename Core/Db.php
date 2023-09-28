<?php

namespace Core;

use Core\Config;

class Db {

	public static function query($q, $json = false) {
		$result = Config::db()->query($q);
		if ($json == true) {
			return Functions::json($result);
		} else {
			return $result;
		}
	}

	public static function insert($q, $p = []) {
		$mysqli = Config::db();
		$stmt = $mysqli->prepare($q);
		$stmt->execute($p);
		return $stmt->insert_id;
	}

	public static function update($q, $p = []) {
		$mysqli = Config::db();
		$stmt = $mysqli->prepare($q);
		$stmt->execute($p);
		return $stmt->insert_id;
	}

	public static function getResults($q, $p = [], $json = false) {
		$mysqli = Config::db();
		$stmt = $mysqli->prepare($q);
		$stmt->execute($p);
		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		if ($json == true) {
			return Functions::json($result);
		} else {
			return $result;
		}

	}

}