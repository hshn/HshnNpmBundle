<?php

namespace Hshn\NpmBundle\Functional;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmInstallCommandTest extends NpmCommandTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->deleteDirectory([
            $this->getBundleNpmDir('FooBundle').'/node_modules',
            $this->getBundleNpmDir('BarBundle').'/node_modules'
        ]);
    }

    /**
     * @test
     */
    public function testInstall()
    {
        self::bootKernel(['config' => __DIR__.'/config/legacy.yml']);

        $this->assertFileNotExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileNotExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');

        $output = $this->runCommand(['hshn:npm:install']);

        $this->assertContains('[FooBundle]', $output);
        $this->assertContains('lodash@4.17.11', $output);
        $this->assertContains('[BarBundle]', $output);
        $this->assertContains('underscore@1.8.3', $output);

        $this->assertFileExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');
    }

    /**
     * @test
     */
    public function testInstallNpm()
    {
        self::bootKernel(['config' => __DIR__.'/config/npm.yml']);

        $this->assertFileNotExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileNotExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');

        $output = $this->runCommand(['hshn:npm:install']);

        $this->assertContains('[FooBundle] yarn install', $output);
        $this->assertContains('[BarBundle]', $output);
        $this->assertContains('underscore@1.8.3', $output);

        $this->assertFileExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');
    }

    /**
     * @test
     */
    public function testInstallYarn()
    {
        self::bootKernel(['config' => __DIR__.'/config/yarn.yml']);

        $this->assertFileNotExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileNotExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');

        $output = $this->runCommand(['hshn:npm:install']);

        $this->assertContains('[FooBundle]', $output);
        $this->assertContains('lodash@4.17.11', $output);
        $this->assertContains('[BarBundle] yarn install', $output);

        $this->assertFileExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');
    }

    /**
     * @test
     */
    public function testInstallWithBundleSpecifies()
    {
        self::bootKernel(['config' => __DIR__.'/config/npm.yml']);

        $this->assertFileNotExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileNotExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');

        $output = $this->runCommand(['hshn:npm:install', '--bundle=FooBundle']);

        $this->assertContains('[FooBundle] yarn install', $output);
        $this->assertNotContains('[BarBundle]', $output);
        $this->assertNotContains('underscore@1.8.3', $output);

        $this->assertFileExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileNotExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');
    }
}
