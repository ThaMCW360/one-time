<?php

namespace Core;

class View {

	public static function render($view, $vars = []) {

		$view = preg_replace('/\.{1}/', '/', $view);
		$file = "../App/Views/$view";
		if (is_readable($file . ".html")) {
			extract($vars);
			include $file . ".html";

		} elseif (is_readable($file . ".php")) {
			extract($vars);
			include $file . ".php";
		} else {
			throw new \Exception("$file not found");
		}
	}

	public static function renderTwig($template, $args = []) {
		static $twig = null;

		if ($twig === null) {
			$loader = new \Twig_Loader_Filesystem('../App/Views');
			$twig = new \Twig_Environment($loader);
		}

		echo $twig->render($template, $args);
	}

	public static function json($array) {
		header('Content-Type: application/json');
		return json_encode($array);
	}

}