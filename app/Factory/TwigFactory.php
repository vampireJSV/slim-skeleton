<?php namespace App\Factory;

use DI\Bridge\Slim\App;
use Dtkahl\SimpleConfig\Config;
use Gettext\Translations;
use Gettext\Translator;
use nochso\HtmlCompressTwig;
use Parsedown;
use Slim\Flash\Messages;
use Slim\Router;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use SlimSession\Helper;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigFactory
{

    public function __invoke(
        Config $config,
        Router $router,
        Messages $messages,
        Helper $session,
        App $app
    ) {
        $manifest      = [];
        $manifest_file = APP_ROOT."/public/build/manifest.json";
        if (file_exists($manifest_file)) {
            $manifest = json_decode(file_get_contents($manifest_file), true);
        }

        $twig = new Twig($config->get("app.twig.path"), [
            "cache"      => getenv("DEBUG") ? false : $config->get("app.twig.cache"),
            'debug'      => getenv("DEBUG"),
            'autoescape' => false,
        ]);

        $twig->addExtension(new TwigExtension($router, ""));

        $twig->addExtension(new HtmlCompressTwig\Extension());

        $twig->offsetSet("debug", getenv("DEBUG"));

        $twig->getEnvironment()->addFunction(new TwigFunction('flash_alert', function () use ($messages) {
            $messages = $messages->getMessage('alert');
            $output   = '';
            if (isset($messages['alert'][0]) && $messages['alert'][0] != '') {
                $output = $messages['alert'][0];
            }

            return $output;
        }));

        $twig->getEnvironment()->addFunction(new TwigFunction('favicon_block', function () use ($messages) {
            $output = '';

            if (file_exists(APP_ROOT."/resources/assets/favicon/html_code.html")) {
                $output = file_get_contents(APP_ROOT."/resources/assets/favicon/html_code.html");
            }

            return $output;
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('config', function ($value) use ($config) {
            return $config->get($value);
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('asset', function ($file_name) use ($manifest, $config) {
            if (array_key_exists($file_name, $manifest)) {
                $file_name = $manifest[$file_name];
            }

            return mount_url().$config->get('app.assets.path').$file_name;
        }));

        $twig->getEnvironment()->addFilter(new TwigFilter('url', function ($string) {
            return mount_url().'/'.$string;
        }));


        if ($config->get('i18n.enable')) {

            $localeApp = $session->get('locale', $config->get('i18n.lang'));

            putenv('LC_ALL='.$localeApp);
            $translations               = null;
            $auto_generate_translations = false;
            if (getenv("DEBUG")) {
                $file_path = $config->get('i18n.path').$localeApp.'.po';
                if ( ! file_exists($file_path)) {
                    $translations = new Translations();
                    $translations->toPoFile($file_path);
                }
                if ($config->get('i18n.auto_generate')) {
                    $auto_generate_translations = true;
                    $translations               = new Translations();
                    $translations->addFromPoFile($file_path);
                }
                $texts = Translations::fromPoFile($file_path);
            } else {
                if (file_exists($config->get('i18n.path').$localeApp.'.mo')) {
                    $texts = Translations::fromMoFile($config->get('i18n.path').$localeApp.'.mo');
                } else {
                    $translations = new Translations();
                    $translations->addFromPoFile($config->get('i18n.path').$localeApp.'.po');
                    $translations->toMoFile($config->get('i18n.path').'/'.$localeApp.'.mo');
                    $texts = Translations::fromMoFile($config->get('i18n.path').$localeApp.'.mo');
                }

            }

            $translate = new Translator();
            $translate->loadTranslations($texts);
            $translate->register();


            $twig->getEnvironment()->addFilter(new TwigFilter('trans',
                function ($string) use ($localeApp, $config, $auto_generate_translations, $translations) {
                    if ($string == "") {
                        return "";
                    }
                    if ($auto_generate_translations) {
                        $translation = $translations->find(null, $string);
                        if ($translation == false) {
                            $translations->insert(null, $string);
                            $translations->toPoFile($config->get('i18n.path').'/'.$localeApp.'.po');
                        }
                    }
                    if ($config->get('i18n.validate_text')) {
                        $words = str_word_count($string, 1);
                        foreach ($words as $word) {
                            $string = str_replace($word, str_pad("", strlen($word), "X"), $string);
                        }

                        $return = $string;
                    } else {
                        $return = __($string);
                    }
                    if ($config->get('i18n.markdown_translations')) {
                        $return = Parsedown::instance()->setBreaksEnabled(true)->setMarkupEscaped(true)->line($return);
                    }

                    return $return;

                }));

            $twig->getEnvironment()->addFilter(new TwigFilter('set_lang_link', function ($string) use ($config) {
                return mount_url().$config->get('i18n.url_set_language').'/'.$string;
            }));

            $twig->getEnvironment()->addFunction(new TwigFunction('lang_enable', function () use ($localeApp) {
                return $localeApp;
            }));

            $twig->getEnvironment()->addFunction(new TwigFunction('lang_enable_name',
                function () use ($localeApp, $config) {
                    return $config->get('i18n.languages')[$localeApp];
                }));

            $twig->getEnvironment()->addFunction(new TwigFunction('languages', function () use ($config) {
                return $config->get('i18n.languages');
            }));


        } else {
            $twig->getEnvironment()->addFilter(new TwigFilter('trans',
                function ($string) {
                    return $string;

                }));

            $twig->getEnvironment()->addFilter(new TwigFilter('set_lang_link', function ($string, $config) {
                return mount_url().$config->get('i18n.url_set_language').'/'.$string;
            }));

            $twig->getEnvironment()->addFunction(new TwigFunction('lang_enable', function () use ($config) {
                return $config->get('i18n.lang');
            }));

            $twig->getEnvironment()->addFunction(new TwigFunction('lang_enable_name',
                function () use ($config) {
                    return $config->get('i18n.languages')[$config->get('i18n.lang')];
                }));

            $twig->getEnvironment()->addFunction(new TwigFunction('languages', function () use ($config) {
                return $config->get('i18n.languages');
            }));
        }

        return $twig;
    }

}
