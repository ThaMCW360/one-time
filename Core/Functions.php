<?php

namespace Core;

// use Core\Config;

class Functions {
	public static function Redirect($url, $permanent = false) {
		if (headers_sent() === false) {
			header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
		}

		exit();
	}

	public static function echo ($s) {
		echo $s;
	}

	public static function queryStr($params) {
		$string = '';
		foreach ($params as $key => $value) {
			$string = $string . "$key=$value&";
		}
		return substr($string, 0, strlen($string) - 1);
	}

	public static function json($arr) {
		return json_decode(json_encode($arr, false));
	}

	public static function redirectTo($destiny, $params = [], $vars = []) {
		if (count($params) > 0) {
			$destiny = $destiny . "?" . Functions::queryStr($params);
		}
		if (count($vars) > 0) {
			if (count($params) > 0) {
				$destiny = $destiny . "&";
			} else {
				$destiny = $destiny . "?";
			}
			$destiny = $destiny . "vars=" . json_encode($vars, true);
		}

		header("Location: $destiny");
	}

	public static function backUrl() {
		$url = str_replace(Functions::getBaseUrl(), "", $_SERVER['HTTP_REFERER']);
		return $url;
	}

	public static function include($view, $arr = []) {
		$view = preg_replace('/\.{1}/', '\\', $view);
		$view = preg_replace('/\/{1}/', '\\', $view);
		$file = "../App/Views/$view"; // relative to Core directory
		// echo $file;
		if (is_readable($file . ".html")) {
			extract($arr);
			// require $file;
			include $file . ".html";

		} elseif (is_readable($file . ".php")) {
			extract($arr);
			// require $file;
			include $file . ".php";
		}
	}

	public static function getBaseUrl() {
		$baseDir = Config::BASEDIR;
		return $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $baseDir;
	}

}
