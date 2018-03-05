<?php

namespace common\components\tools\sendcloud\lib\util;

class Response {
	private $body;
	
	public function __construct($body = null) {
		$this->body = $body;
	}
	public function body() {
		return $this->body;
	}
}