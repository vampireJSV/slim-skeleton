<?php namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class MainController
{

    public function getHome(Request $request, Response $response, Twig $twig)
    {
        return $twig->render($response, "home.twig");
    }

}
