<?php
if (PHP_SAPI=="cli-server") {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER["REQUEST_URI"]);
    $file = __DIR__.$url["path"];
    if (is_file($file)) {
        return false;
    }
}


const APP_ROOT = __DIR__."/..";
require APP_ROOT."/app/helpers.php";

$session_path = scan_dir_to_array(APP_ROOT."/config/")['app']['sessions']['path'];
clean_old_sessions($session_path);
session_save_path($session_path);
session_start();

require APP_ROOT."/vendor/autoload.php";
(new \Dotenv\Dotenv(APP_ROOT))->load();

// Instantiate the app
$app = new class() extends \DI\Bridge\Slim\App {
    protected function configureContainer(\DI\ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            "settings.httpVersion"                       => "2.0",
            "settings.responseChunkSize"                 => 4096,
            "settings.outputBuffering"                   => "append",
            "settings.displayErrorDetails"               => getenv("DEBUG"),
            "settings.determineRouteBeforeAppMiddleware" => true,
        ]);
    }
};
if (scan_dir_to_array(APP_ROOT."/config/")['app']['orm'] && getenv('DB_DRIVER')!='') {
    require APP_ROOT.'/app/rb.php';
    if (getenv('DB_DRIVER')!='sqlite' && getenv('DB_SERVER')!='' && getenv('DB_USER')!='') {
        R::setup(getenv('DB_DRIVER').':host='.getenv('DB_SERVER').';dbname='.getenv('DB_NAME'),
            getenv('DB_USER'), getenv('DB_PASS'));
    } elseif (getenv('DB_NAME')!='') {

        R::setup('sqlite:'.APP_ROOT.'/storage/'.getenv('DB_NAME').'.db');

    } else {
        R::setup('sqlite:'.APP_ROOT.'/storage/dates.db');
    }
    if (getenv('DEBUG')==0) {
        R::freeze(true);
    } else {
        R::fancyDebug(true);
    }

}
/** @var \DI\Container $c */
$c = $app->getContainer();

if (getenv('DEBUG')) {
    PhpConsole\Helper::register();
}

require APP_ROOT."/app/dependencies.php";
require APP_ROOT."/app/routes.php";
require APP_ROOT."/app/middleware.php";

if (scan_dir_to_array(APP_ROOT."/config/")['i18n']['enable']) {
    $app->get(scan_dir_to_array(APP_ROOT."/config/")['i18n']['url_set_language'].'/{ref}',
        function (
            $ref,
            Slim\Http\Response $response,
            \SlimSession\Helper $session,
            \Slim\Flash\Messages $flash
        ) {

            $session->locale = $ref;
            if (is_null($flash->getMessage('history'))) {
                $url = mount_url();
            } else {
                $url = $flash->getMessage('history')[0];
            }


            return $response->withStatus(302)->withHeader('Location', $url);
        });
}

$app->post('/'.scan_dir_to_array(APP_ROOT."/config/")['web']['send_url'],
    function (
        Slim\Http\Response $response,
        Slim\Http\Request $request,
        \PHPMailer $mail,
        \Slim\Flash\Messages $flash
    ) {
        $admin      = scan_dir_to_array(APP_ROOT."/config/")['mail']['admin'];
        $web_config = scan_dir_to_array(APP_ROOT."/config/")['web'];
        $mail->addAddress($admin['email'], $admin['name']);
        $mail->Subject = $web_config['contact_subject'];
        $text          = '';
        foreach ($request->getParams() as $clave => $valor) {
            $text .= $clave.':'.$valor."
";
        }
        $mail->Body = $text;

        if ( ! $mail->send()) {
            $response->withStatus(500);
        }

        return $response->withStatus(302)->withHeader('Location',
            $flash->getMessage('history')[0].'#'.$web_config['ok_send_hash']);
    });

$app->run();
