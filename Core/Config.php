<?php
namespace Core;
use \MySQLi;

class Config {

	const BASEDIR = "";
	const SHOW_ERRORS = true;
	const TITLE = "One-Time";

	public static function db() {
		$db = ['hostname' => '127.0.0.1',
			'username' => 'root',
			'password' => '',
			'database' => 'one-time'];
		$db = json_decode(json_encode($db, false));
		return new MySQLi($db->hostname, $db->username, $db->password, $db->database);
	}

	public static function url() {
		$request = $_SERVER['REQUEST_URI'];
		$request = substr_replace($request, "", 0, strlen(Config::BASEDIR));
		$reqUri = preg_replace("/(^\/)|(\/$)/", "", $request);
		if (strpos($reqUri, "?") > 0 || strpos($reqUri, "?") === 0) {
			$reqUri = substr_replace($reqUri, "", strpos($reqUri, "?"));
		}
		return $reqUri;
	}

}