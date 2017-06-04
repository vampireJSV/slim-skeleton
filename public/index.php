<?php
if (PHP_SAPI == "cli-server") {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER["REQUEST_URI"]);
    $file = __DIR__ . $url["path"];
    if (is_file($file)) {
        return false;
    }
}

session_start();
const APP_ROOT = __DIR__ . "/..";

require APP_ROOT . "/vendor/autoload.php";
(new \Dotenv\Dotenv(APP_ROOT))->load();

// Instantiate the app
$app = new class() extends \DI\Bridge\Slim\App {
    protected function configureContainer(\DI\ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            "settings.httpVersion" => "2.0",
            "settings.responseChunkSize" => 4096,
            "settings.outputBuffering" => "append",
            "settings.displayErrorDetails" => getenv("DEBUG"),
            "settings.determineRouteBeforeAppMiddleware" => true // must be true for error handling etc.
        ]);
    }
};
/** @var \DI\Container $c */
$c = $app->getContainer();

require APP_ROOT . "/app/dependencies.php";
require APP_ROOT . "/app/middleware.php";
require APP_ROOT . "/app/routes.php";

$app->run();
