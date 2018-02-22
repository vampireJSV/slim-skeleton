<?php namespace App\Factory;

use Slim\Flash\Messages;

class MessagesFactory {

	public function __invoke() {
		return new Messages();
	}

}