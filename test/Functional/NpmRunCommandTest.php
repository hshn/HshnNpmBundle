<?php

namespace Hshn\NpmBundle\Functional;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmRunCommandTest extends NpmCommandTestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->deleteDirectory([
            $this->getBundleDir('GulpBundle').'/Resources/public/dist',
        ]);
    }

    public function testRunBuild()
    {
        self::bootKernel(['config' => __DIR__.'/config/legacy.yml']);

        $sourcePath = $this->getBundleDir('GulpBundle').'/Resources/public/dist/app.js';
        $sourceMapPath = $sourcePath.'.map';

        self::assertFileNotExists($sourcePath);
        self::assertFileNotExists($sourceMapPath);

        $this->runCommand(['hshn:npm:install', '--bundle', 'GulpBundle']);
        $output = $this->runCommand(['hshn:npm:run', 'build', '--bundle', 'GulpBundle']);

        self::assertContains("npm' 'run' 'build'", $output);
        self::assertContains('[GulpBundle]', $output);
        self::assertContains('gulp build', $output);

        self::assertFileExists($sourcePath);
        self::assertFileExists($sourceMapPath);
    }

    public function testRunBuildYarn()
    {
        self::bootKernel(['config' => __DIR__.'/config/yarn.yml']);

        $sourcePath = $this->getBundleDir('GulpBundle').'/Resources/public/dist/app.js';
        $sourceMapPath = $sourcePath.'.map';

        self::assertFileNotExists($sourcePath);
        self::assertFileNotExists($sourceMapPath);

        $this->runCommand(['hshn:npm:install', '--bundle', 'GulpBundle']);
        $output = $this->runCommand(['hshn:npm:run', 'build', '--bundle', 'GulpBundle']);

        self::assertContains("yarn' 'run' 'build'", $output);
        self::assertContains('[GulpBundle]', $output);
        self::assertContains('gulp build', $output);

        self::assertFileExists($sourcePath);
        self::assertFileExists($sourceMapPath);
    }

    public function testRunBuildNpm()
    {
        self::bootKernel(['config' => __DIR__.'/config/npm.yml']);

        $sourcePath = $this->getBundleDir('GulpBundle').'/Resources/public/dist/app.js';
        $sourceMapPath = $sourcePath.'.map';

        self::assertFileNotExists($sourcePath);
        self::assertFileNotExists($sourceMapPath);

        $this->runCommand(['hshn:npm:install', '--bundle', 'GulpBundle']);
        $output = $this->runCommand(['hshn:npm:run', 'build', '--bundle', 'GulpBundle']);

        self::assertContains("npm' 'run' 'build'", $output);
        self::assertContains('[GulpBundle]', $output);
        self::assertContains('gulp build', $output);

        self::assertFileExists($sourcePath);
        self::assertFileExists($sourceMapPath);
    }
}
