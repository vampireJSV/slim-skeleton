<?php namespace App\Middleware;

use Dtkahl\SimpleConfig\Config;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ViewMiddleware
{
    private $config;
    private $twig;

    public function __construct(Config $config, Twig $twig)
    {

        $this->config = $config;
        $this->twig   = $twig;

    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (is_null($request->getAttribute("route"))) {
            $string = substr($request->getUri()->getPath(), 1);
            $args   = argsToArray($string);
            $pages  = ['index.twig'];
            if ($string != '') {
                $pages = [];
                $page  = '';
                foreach ($args as $value) {
                    $pages[] = $page.$value.'.twig';
                    $page    .= $value.DIRECTORY_SEPARATOR;
                }
                $pages = array_reverse($pages);
            }
            $output = false;
            foreach ($pages as $page) {
                if (strpos($page, '.html.twig') !== false) {
                    $page = str_replace('.html.twig', '.twig', $page);
                }

                if (file_exists($this->config->get('app.twig.path').$page)) {
                    $response = $response->withStatus(200);
                    $this->twig->render($response, $page, ['args' => $args]);
                    $output = true;
                    break;
                }
            }
            if ( ! $output) {
                $response = $response->withStatus(404);
                $this->twig->render($response, "error.twig", ['message' => 'Page Not Found']);
            }

        } else {
            $response = $next($request, $response);
        }

        return $response;
    }

}