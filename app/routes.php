<?php
/** @var \Slim\App $app */

$app->get("/", ['\App\Controller\MainController', 'getHome']);
