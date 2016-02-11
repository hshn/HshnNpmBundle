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
        $sourcePath = $this->getBundleDir('GulpBundle').'/Resources/public/dist/app.js';
        $sourceMapPath = $sourcePath.'.map';

        $this->assertFileNotExists($sourcePath);
        $this->assertFileNotExists($sourceMapPath);

        $this->runCommand(['hshn:npm:install', '--bundle', 'GulpBundle']);
        $output = $this->runCommand(['hshn:npm:run', 'build', '--bundle', 'GulpBundle']);

        $this->assertContains('[GulpBundle]', $output);
        $this->assertContains('gulp build', $output);

        $this->assertFileExists($sourcePath);
        $this->assertFileExists($sourceMapPath);
    }
}
