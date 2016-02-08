<?php

namespace Hshn\NpmBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class InstallCommandTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();

        static::bootKernel();

        $dirs = [
            __DIR__.'/Bundle/FooBundle/Resources/npm/node_modules',
            __DIR__.'/Bundle/BarBundle/Resources/npm/node_modules',
        ];

        $fs = new Filesystem();
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
     * @test
     */
    public function testInstall()
    {
        $this->assertFileNotExists(__DIR__.'/Bundle/FooBundle/Resources/npm/node_modules/lodash');
        $this->assertFileNotExists(__DIR__.'/Bundle/BarBundle/Resources/npm/node_modules/underscore');

        $app = new Application(static::$kernel);
        $app->setAutoExit(false);
        $app->run(new ArrayInput(['hshn:npm:install']), $out = new BufferedOutput());

        $output = $out->fetch();
        $this->assertContains('[FooBundle]', $output);
        $this->assertContains('lodash@4.0.0', $output);
        $this->assertContains('[BarBundle]', $output);
        $this->assertContains('underscore@1.8.3', $output);

        $this->assertFileExists(__DIR__.'/Bundle/FooBundle/Resources/npm/node_modules/lodash');
        $this->assertFileExists(__DIR__.'/Bundle/BarBundle/Resources/npm/node_modules/underscore');
    }
}
