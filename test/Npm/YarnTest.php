<?php

namespace Hshn\NpmBundle\Npm;


use Prophecy\Prophecy\ObjectProphecy;

class YarnTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Yarn
     */
    private $npm;

    protected function setUp()
    {
        parent::setUp();

        $this->npm = new Yarn('echo');
    }

    /**
     * @test
     */
    public function testInstall()
    {
        $configuration = $this->getConfiguration();
        $configuration->getDirectory()->willReturn(__DIR__);

        $process = $this->npm->install(['foo'], $configuration->reveal());

        $this->assertEquals("'echo' 'foo'", $process->getCommandLine());

        $process->mustRun();
        $this->assertEquals('foo', trim($process->getOutput()));
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
     * @return ObjectProphecy|ConfigurationInterface
     */
    private function getConfiguration()
    {
        return $this->prophesize(ConfigurationInterface::class);
    }
}
