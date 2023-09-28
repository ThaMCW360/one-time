<?php

namespace App\Controllers;
use Core\Config;
use Core\Controller as BaseController;
use Core\View;

class Home extends BaseController {
	public function index($param, $request) {
		$title = Config::TITLE;
		return View::render("layout.head")
		. View::render("home", ["title" => $title])
		. View::render("layout.footer");
	}
}
