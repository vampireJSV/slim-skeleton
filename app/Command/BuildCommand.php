<?php namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand
{
    public function __invoke(OutputInterface $output, \Slim\Views\Twig $twig)
    {
        shell_exec("npm run build");
        $dir = scandir('resources/views');
        foreach ($dir as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'twig') {
                $content = $twig->fetch($file);
                file_put_contents('public/'.pathinfo($file, PATHINFO_FILENAME).'.html', $content);
            }
        }
        $zipFile = new \PhpZip\ZipFile();
        $dir     = scandir('public');
        foreach ($dir as $file) {
            if ($file != 'index.php' && $file != ".htaccess" && $file != '.' && $file != "..") {
                if (is_dir('public/'.$file)) {
                    $zipFile->addDirRecursive('public/'.$file, "/".$file);
                } else {
                    $zipFile->addFile('public/'.$file, '/'.$file);
                }
            }
        }
        $zipFile->saveAsFile('public/build.zip')// save the archive to a file
                ->close(); // close archive

    }
}