<?php return [
    "orm"    => true,
    "logger" => [
        "name" => "app",
        "path" => APP_ROOT."/storage/logs/app.log",
    ],

    "twig" => [
        "path"  => APP_ROOT."/resources/views/",
        "cache" => APP_ROOT."/storage/views",
    ],

    "cache" => [
        "path" => APP_ROOT."/storage/cache/",
    ],

    "sessions" => [
        "path" => APP_ROOT."/storage/sessions/",
    ],

    "assets" => [
        "path" => "/build/"
    ]
];