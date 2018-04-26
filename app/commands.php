<?php

/** @var \Silly\Edition\PhpDi\Application $app */

$app->command( "app:up", new \App\Command\UpCommand );
$app->command( "app:dev", new \App\Command\DevCommand );
$app->command( "app:down", new \App\Command\DownCommand );
$app->command( "app:build", new \App\Command\BuildCommand );
$app->command( "cache:clear", new \App\Command\CacheClearCommand );
$app->command( "sessions:clear", new \App\Command\SessionsClearCommand );