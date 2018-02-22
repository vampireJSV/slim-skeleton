<?php namespace App\Middleware;

use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;

class HistoryMiddleware {
	private $flash;

	public function __construct( Messages $flash ) {
		$this->flash = $flash;
	}

	public function __invoke( Request $request, Response $response, callable $next ) {
		$this->flash->addMessage( 'history', $request->getUri() );
		$response = $next( $request, $response );

		return $response;
	}

}