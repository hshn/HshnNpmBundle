<?php

namespace Hshn\NpmBundle\Npm;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Npm
     */
    private $npm;

    protected function setUp()
    {
        parent::setUp();

        $this->npm = new Npm('echo');
    }

    /**
     * @test
     */
    public function testInstall()
    {
        $configuration = $this->getConfiguration();
        $configuration->getDirectory()->willReturn(__DIR__);

        $process = $this->npm->install(['foo'], $configuration->reveal());

        $this->assertEquals("'echo' 'install' 'foo'", $process->getCommandLine());

        $process->mustRun();
        $this->assertEquals('install foo', trim($process->getOutput()));
    }

    /**
     * @test
     */
    public function testRun()
    {
        $configuration = $this->getConfiguration();
        $configuration->getDirectory()->willReturn(__DIR__);

        $process = $this->npm->run(['foo'], $configuration->reveal());

        $this->assertEquals("'echo' 'run' 'foo'", $process->getCommandLine());

        $process->mustRun();
        $this->assertEquals('run foo', trim($process->getOutput()));
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy|ConfigurationInterface
     */
    private function getConfiguration()
    {
        return $this->prophesize(ConfigurationInterface::class);
    }
}
