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
        $this->assertFileNotExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileNotExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');

        $output = $this->runCommand(['hshn:npm:install']);

        $this->assertContains('[FooBundle]', $output);
        $this->assertContains('lodash@4.0.0', $output);
        $this->assertContains('[BarBundle]', $output);
        $this->assertContains('underscore@1.8.3', $output);

        $this->assertFileExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');
    }

    /**
     * @test
     */
    public function testInstallWithBundleSpecifies()
    {
        $this->assertFileNotExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileNotExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');

        $output = $this->runCommand(['hshn:npm:install', '--bundle=FooBundle']);

        $this->assertContains('[FooBundle]', $output);
        $this->assertContains('lodash@4.0.0', $output);
        $this->assertNotContains('[BarBundle]', $output);
        $this->assertNotContains('underscore@1.8.3', $output);

        $this->assertFileExists($this->getBundleNpmDir('FooBundle').'/node_modules/lodash');
        $this->assertFileNotExists($this->getBundleNpmDir('BarBundle').'/node_modules/underscore');
    }
}
