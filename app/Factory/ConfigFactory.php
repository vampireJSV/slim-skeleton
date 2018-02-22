<?php namespace App\Factory;

use Dtkahl\SimpleConfig\Config;

class ConfigFactory {

	public function __invoke() {
		return new Config( scan_dir_to_array( APP_ROOT . "/config/" ) );
	}

}