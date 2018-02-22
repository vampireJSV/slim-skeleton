# Slim Framework 3 Skeleton Application

Use this skeleton application for the slim 3 micro-framework with some pre configured dependencies and structures:

- [Twig](https://github.com/twigphp/Twig) as View engine
- [Monolog](https://github.com/monolog/monolog) for logging
- [PHP DI](https://github.com/PHP-DI/PHP-DI) for dependency injection
- [Silly CLI micro-framework](https://github.com/mnapoli/silly) for CLI Commands
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) to load environment configuration from ".env" file
- [dtkahl/php-simple-config](https://github.com/dtkahl/php-simple-config) for simple access to configuration files
- [dtkahl/php-file-cache](https://github.com/dtkahl/php-file-cache) as simple file cache
- [Webpack](https://github.com/webpack/webpack) to bundle assets and build SCSS 
- [Flash Messages](https://github.com/slimphp/Slim-Flash) to Flash sessions messages 
- [PHPMailer](https://github.com/PHPMailer/PHPMailer) to send Emails 
- [Session](https://github.com/bryanjhv/slim-session) to management sessions 
- [Gettext](https://github.com/oscarotero/Gettext) for languages support
- [Parsedown](https://github.com/erusev/parsedown) for markdown support in translations
- [Twig Compress](https://github.com/nochso/html-compress-twig) compress twig output
- Controller, Middleware and Factory classes
- Exception handling
- Maintenance mode

If you don't need all of this, you should just use [slimphp/Slim-Skeleton](https://github.com/slimphp/Slim-Skeleton) which I forked and extended with this features.

## Requirements

- PHP >= 7.0
- Composer
- NodeJS (for Webpack)

## Node, Javascript and webpack libraries support

Javascript and client
- [Jquery](https://github.com/jquery/jquery)
- [Jquery-migrate](https://github.com/jquery/jquery-migrate)
- [Bootstrap](https://github.com/twbs/bootstrap)
- [Bootbox](https://github.com/makeusabrew/bootbox)
- [Bootstrap Lightbox](https://github.com/ashleydw/lightbox)
- [Slick carousel](https://github.com/kenwheeler/slick/)

Node
- [cli-real-favicon](https://github.com/RealFaviconGenerator/cli-real-favicon)
- [dotenv](https://github.com/motdotla/dotenv)

Webpack
- [Clean for WebPack](https://github.com/johnagan/clean-webpack-plugin)
- [Copy Webpack Plugin](https://github.com/webpack-contrib/copy-webpack-plugin)
- [Image compress](https://github.com/tcoopman/image-webpack-loader)
- [Webfonts generator](https://github.com/jeerbl/webfonts-loader)



## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

```
    composer create-project creativados/slim-skeleton [my-app-name]
```

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

- Point your virtual host document root to your new application's `public/` directory.
- Ensure `storage/` is web writeable.
- create a copy ".env" of the file ".env.example" an set up your configuration

Install NodeJS dependencies:

```
npm install
```


## Development

for development you can use the PHP built-in webserver:

```
    php cli app:dev
```

Generate favicon for resources/assets/img/logo.png:

```
    npm run favicon
```

And let webpack bundle your assets live:

```
    npm run watch
```


## On Production

Make sure to bundle your assets after clone/update your assets on production by running:

```
    npm run build
```
