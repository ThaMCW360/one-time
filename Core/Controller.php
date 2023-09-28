<?php

namespace Core;

abstract class Controller {
	protected $request = [];
	protected $params = [];

	public function __construct($param) {
		$this->request = $_REQUEST;
		$this->param = $param;
	}

}
