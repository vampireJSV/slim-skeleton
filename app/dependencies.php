<?php
/**
 * @var \DI\Container $c
 */

$c->set( \Dtkahl\SimpleConfig\Config::class, DI\factory( new \App\Factory\ConfigFactory ) );
$c->set( \Monolog\Logger::class, DI\factory( new \App\Factory\MonologFactory ) );
$c->set( \Slim\Views\Twig::class, DI\factory( new \App\Factory\TwigFactory ) );
$c->set( \Dtkahl\FileCache\Cache::class, DI\factory( new \App\Factory\CacheFactory ) );
$c->set( \Slim\Flash\Messages::class, DI\factory( new \App\Factory\MessagesFactory ) );
$c->set( \SlimSession\Helper::class, DI\factory( new \App\Factory\SessionFactory ) );