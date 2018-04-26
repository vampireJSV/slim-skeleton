<?php namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand
{
    public function __invoke(OutputInterface $output, \Slim\Views\Twig $twig)
    {
        $dir = scandir('resources/views');
        foreach ($dir as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'twig') {
                $content = $twig->fetch($file);
                file_put_contents('public/'.pathinfo($file, PATHINFO_FILENAME).'.html', $content);
            }
        }

    }
}