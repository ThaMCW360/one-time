<?php

namespace Core;

class Autoload {
	public static function class () {
		spl_autoload_register(function ($class) {
			$root = dirname(__DIR__);
			$file = $root . '/' . str_replace('\\', '/', $class) . '.php';
			if (is_readable($file)) {
				require_once $file;
			}
		});
	}

}