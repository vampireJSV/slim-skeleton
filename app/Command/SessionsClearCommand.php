<?php namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;

class SessionsClearCommand {
	public function __invoke( OutputInterface $output ) {
		$storage = APP_ROOT . "/storage";
		shell_exec( "ls -ad $storage/sessions/* | grep -v '.gitignore' | grep -vw '.' | xargs rm -rf" );
		$output->writeln( "Sessions cleared!" );
	}
}