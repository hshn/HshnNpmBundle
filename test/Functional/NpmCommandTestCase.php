<?php

namespace Hshn\NpmBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmCommandTestCase extends WebTestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        static::bootKernel();
    }

    /**
     * @param $name
     * @return string
     */
    protected function getBundleDir($name)
    {
        return __DIR__.'/Bundle/'.$name;
    }

    /**
     * @param $name
     * @return string
     */
    protected function getBundleNpmDir($name)
    {
        return $this->getBundleDir($name).'/Resources/npm';
    }

    /**
     * @param array|string $directory
     */
    protected function deleteDirectory($directory)
    {
        $fs = new Filesystem();

        $dirs = is_array($directory) ? $directory : [$directory];
        $dirs = array_filter($dirs, function ($dir) use ($fs) {
            return $fs->exists($dir);
        });

        $files = new \AppendIterator();
        foreach ($dirs as $dir) {
            $files->append(new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            ));
        }

        try {
            $fs->remove($files);
        } catch (\Exception $e) {
        }
    }

    /**
     * @param array $args
     * @return string output
     */
    protected function runCommand(array $args)
    {
        $app = new Application(static::$kernel);
        $app->setAutoExit(false);
        $code = $app->run(new ArrayInput($args), $out = new BufferedOutput());

        $output = $out->fetch();
        $this->assertEquals(0, $code, sprintf('The command "%s" was failed. (code: %d) %s', implode(' ', $args), $code, $output));

        return $output;
    }
}
