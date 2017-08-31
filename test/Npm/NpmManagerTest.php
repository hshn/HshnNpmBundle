<?php

namespace Hshn\NpmBundle\Npm;

use Prophecy\Argument;
use Symfony\Component\Process\Process;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NpmManager
     */
    private $manager;

    /**
     * @var ConfigurationInterface[]|\PHPUnit_Framework_MockObject_MockObject[]
     */
    private $configurations;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->manager = new NpmManager($this->configurations = [
            $this->mockConfiguration('foo'),
            $this->mockConfiguration('bar'),
        ]);
    }

    /**
     * @test
     */
    public function testRun()
    {
        $args = ['foo' => 'bar'];

        $this->configurations[0]->expects(self::once())->method('run')->with($args)->willReturn($this->mockProcess());
        $this->configurations[1]->expects(self::once())->method('run')->with($args)->willReturn($this->mockProcess());

        $processes = $this->manager->run($args);

        $this->assertCount(2, $processes);
        $this->assertArrayHasKey('foo', $processes);
        $this->assertArrayHasKey('bar', $processes);
    }

    /**
     * @test
     */
    public function testInstall()
    {
        $args = ['foo' => 'bar'];

        $this->configurations[0]->expects(self::once())->method('install')->with($args)->willReturn($this->mockProcess());
        $this->configurations[1]->expects(self::once())->method('install')->with($args)->willReturn($this->mockProcess());

        $processes = $this->manager->install($args);

        $this->assertCount(2, $processes);
        $this->assertArrayHasKey('foo', $processes);
        $this->assertArrayHasKey('bar', $processes);
    }

    /**
     * @test
     */
    public function testBundles()
    {
        $args = ['foo' => 'bar'];

        $this->configurations[0]->expects(self::once())->method('run')->with($args)->willReturn($this->mockProcess());
        $this->configurations[1]->expects(self::never())->method('run');

        $processes = $this->manager->bundles(['foo'])->run($args);

        $this->assertCount(1, $processes);
        $this->assertArrayHasKey('foo', $processes);
        $this->assertArrayNotHasKey('bar', $processes);
    }

    /**
     * @param string $name
     *
     * @return ConfigurationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function mockConfiguration($name)
    {
        $config = $this->createMock(ConfigurationInterface::class);
        $config->expects(self::any())->method('getName')->willReturn($name);

        return $config;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Process
     */
    private function mockProcess()
    {
        return $this->createMock(Process::class);
    }
}
