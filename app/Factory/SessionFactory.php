<?php namespace App\Factory;

use SlimSession\Helper;

class SessionFactory {

	public function __invoke() {
		return new Helper();
	}

}