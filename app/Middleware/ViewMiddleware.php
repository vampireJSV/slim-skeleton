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
            $args  = argsToArray(substr($request->getUri()->getPath(), 1));
            $args2 = argsToArray(substr($request->getUri()->getBasePath(), 1));
            $page  = 'index.twig';
            if ($args2[0] != '') {
                $page = $args2[0].'.twig';
            }
            if ($args[0] != '') {
                $page = $args[0].'.twig';
            }
            if (strpos($page, '.html.twig') !== false) {
                $page = str_replace('.html.twig', '.twig', $page);
            }

            if (file_exists($this->config->get('app.twig.path').$page)) {
                $response = $response->withStatus(200);
                $this->twig->render($response, $page, ['args' => $args]);
            } else {
                $response = $response->withStatus(404);
                $this->twig->render($response, "error.twig", ['message' => 'Page Not Found']);
            }
        } else {
            $response = $next($request, $response);
        }

        return $response;
    }

}