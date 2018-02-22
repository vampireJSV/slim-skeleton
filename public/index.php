<?php
if ( PHP_SAPI == "cli-server" ) {
	// To help the built-in PHP dev server, check if the request was actually for
	// something which should probably be served as a static file
	$url  = parse_url( $_SERVER["REQUEST_URI"] );
	$file = __DIR__ . $url["path"];
	if ( is_file( $file ) ) {
		return false;
	}
}


const APP_ROOT = __DIR__ . "/..";
require APP_ROOT . "/app/helpers.php";

session_save_path( scan_dir_to_array( APP_ROOT . "/config/" )['app']['sessions']['path'] );
session_start();

require APP_ROOT . "/vendor/autoload.php";
( new \Dotenv\Dotenv( APP_ROOT ) )->load();

// Instantiate the app
$app = new class() extends \DI\Bridge\Slim\App {
	protected function configureContainer( \DI\ContainerBuilder $builder ) {
		$builder->addDefinitions( [
			"settings.httpVersion"                       => "2.0",
			"settings.responseChunkSize"                 => 4096,
			"settings.outputBuffering"                   => "append",
			"settings.displayErrorDetails"               => getenv( "DEBUG" ),
			"settings.determineRouteBeforeAppMiddleware" => true
		] );
	}
};
/** @var \DI\Container $c */
$c = $app->getContainer();

if ( getenv( 'DEBUG' ) ) {
	PhpConsole\Helper::register();
}

require APP_ROOT . "/app/dependencies.php";
require APP_ROOT . "/app/routes.php";
require APP_ROOT . "/app/middleware.php";

$app->get( scan_dir_to_array( APP_ROOT . "/config/" )['i18n']['url_set_language'] . '/{ref}', function ( $ref, Slim\Http\Response $response, $request, \SlimSession\Helper $session, \Slim\Flash\Messages $flash ) {

	$session->locale = $ref;

	return $response->withStatus( 302 )->withHeader( 'Location', $flash->getMessage( 'history' )[0] );
} );

$app->get( '[/{params:.*}]', function ( $params, Slim\Http\Response $response, Slim\Http\Request $request, \Dtkahl\SimpleConfig\Config $config, \Slim\Views\Twig $twig ) {

	$args = argsToArray( $params );
	if ( $args[0] == '' && $request->getUri()->getBasePath() == '' ) {
		$page = 'index.twig';
	} else {
		$page = $args[0] . '.twig';
	}
	if ( strpos( $page, '.html.twig' ) !== false ) {
		$page = str_replace( '.html.twig', '.twig', $page );
	}
	if ( file_exists( $config->get( 'app.twig.path' ) . $page ) ) {
		return $twig->render( $response, "index.twig", [ 'args' => $args ] );
	} else {
		$response->withStatus( 404 );

		return $twig->render( $response, "error.twig", [ 'message' => 'Page Not Found' ] );
	}
} );

$app->run();
