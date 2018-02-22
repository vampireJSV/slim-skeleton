<?php
/** @var \Slim\App $app */

$app->get( "/home", [ '\App\Controller\MainController', 'getHome' ] );

$app->get( '/user/{id}/{name}', [ '\App\Controller\MainController', 'delete' ] );

$app->get( '/hello/{name}', function ( $name, Slim\Http\Response $response, Slim\Http\Request $request, \Slim\Flash\Messages $flash ) {
	$response->getBody()->write( 'Hello ' . $name );

	return $response;
} );