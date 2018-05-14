<?php namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;

class DevCommand {
	public function __invoke( OutputInterface $output ) {
		//shell_exec( "php -S " . mount_url( false ) . " -t public index.php" );
		shell_exec( "php -S " . mount_url( false ) . " -t public" );
	}
}
