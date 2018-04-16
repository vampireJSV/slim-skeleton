<?php return [

    'enable'                => false,
    'auto_generate'         => getenv('LANG_AUTOGENERATE'),
    'validate_text'         => getenv('LANG_VALIDATE_TEXT'),
    'lang'                  => 'es',
    'markdown_translations' => true,
    'path'                  => APP_ROOT.'/resources/i18n/',
    'url_set_language'      => '/set_lang',
    'languages'             => ['es' => 'Español']//[ 'es' => 'Español', "en" => "Inglés" ]

];