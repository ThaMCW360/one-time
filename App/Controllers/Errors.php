<?php

namespace App\Controllers;
use Core\Config;
use Core\Controller as BaseController;
use Core\View;

class Errors extends BaseController {
	public function e404() {
		$title = "Error 404 - " . Config::TITLE;
		return View::render("layout.head", ["title" => $title])
		. View::render("404");
	}

	public function e500() {
		$title = "Error 500 - " . Config::TITLE;
		return View::render("layout.head", ["title" => $title])
		. View::render("500");
	}

	public function addNew() {
		echo 'Hello from the addNew action in the Posts controller!';
	}
}
