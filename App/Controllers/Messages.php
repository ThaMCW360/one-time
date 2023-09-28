<?php

namespace App\Controllers;
use Core\Controller as BaseController;
use Core\Db;
use Core\Functions;
use Core\View;

class Messages extends BaseController {

	public function new ($param, $request) {
		$query = "INSERT INTO messages (content, views, mode, time_to_dest, title, code, admin_code, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$title = $request->title;
		$content = $request->content;
		$time = $request->time;
		$views = 0;
		$mode = 0;
		$code = md5(rand(1, 1000000));
		$admin_code = md5(rand(1, 1000000));
		$now = date('Y-m-d h:i:s');
		Db::insert($query, [$content, $views, $mode, $time, $title, $code, $admin_code, $now, $now]);
		Functions::Redirect('/' . $code . '/' . $admin_code);
	}

	public function success($param) {
		$code = $param->code;
		$adminCode = $param->adminCode;
		$title = "One-Time";
		$message = @Db::getResults("Select * from messages where code =? AND admin_code=?", [$code, $adminCode])[0];
		if (isset($message)) {
			return View::render("layout.head", ["title" => $title])
			. View::render("success", ["message" => $message, "title" => $title])
			. View::render("layout.footer");
		}
	}

	public function expired() {
		$title = "One-Time";
		return View::render("layout.head", ["title" => $title])
		. View::render("expired", ["title" => $title])
		. View::render("layout.footer");
	}

	public function read($param, $request) {
		$message = Db::getResults("Select * from messages where code =?", [$param->code], true);

		if (empty($message)) {
			Functions::Redirect('/expired');
		} else {
			$message = $message[0];
		}

		$message = $message;
		$views = $message->views;
		$time = $message->time_to_dest;
		$title = "One-Time";
		$now = date('Y-m-d h:i:s');

		$query = 'UPDATE messages SET views=?, when_read=? WHERE code=?';
		Db::insert($query, [$views + 1, $now, $param->code]);

		if ($time > 0) {
			header("refresh:" . $time . ";url=/expired");
		}

		if ($views < 1) {
			return View::render("layout.head", ["title" => $title])
			. View::render("message", ["message" => $message, "title" => $title])
			. View::render("layout.footer");
		} else {
			Functions::Redirect('/expired');
		}
	}
}