<?php

namespace Hshn\NpmBundle\Npm;

use Prophecy\Argument;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NpmInterface|\Prophecy\Prophecy\ObjectProphecy;
     */
    private $npm;

    /**
     * @var NpmManager
     */
    private $manager;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->npm = $this->prophesize(Npm::class);
        $this->manager = new NpmManager($this->npm->reveal(), [
            $this->getConfiguration('foo')->reveal(),
            $this->getConfiguration('bar')->reveal(),
        ]);
    }

    /**
     * @test
     */
    public function testRun()
    {
        $processes = $this->manager->install(['foo' => 'bar']);

        $this->assertCount(2, $processes);
        $this->assertArrayHasKey('foo', $processes);
        $this->assertArrayHasKey('bar', $processes);

        $this->npm->install(['foo' => 'bar'], Argument::type(ConfigurationInterface::class))->shouldBeCalledTimes(2);
    }

    /**
     * @test
     */
    public function testInstall()
    {
        $processes = $this->manager->run(['foo' => 'bar']);

        $this->assertCount(2, $processes);
        $this->assertArrayHasKey('foo', $processes);
        $this->assertArrayHasKey('bar', $processes);

        $this->npm->run(['foo' => 'bar'], Argument::type(ConfigurationInterface::class))->shouldBeCalledTimes(2);
    }

    /**
     * @test
     */
    public function testBundles()
    {
        $processes = $this->manager->bundles(['foo'])->run(['foo' => 'bar']);

        $this->assertCount(1, $processes);
        $this->assertArrayHasKey('foo', $processes);
        $this->assertArrayNotHasKey('bar', $processes);

        $this->npm->run(['foo' => 'bar'], Argument::type(ConfigurationInterface::class))->shouldBeCalledTimes(1);
    }

    /**
     * @param string $name
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function getConfiguration($name)
    {
        $configuration = $this->prophesize(ConfigurationInterface::class);
        $configuration->getName()->willReturn($name);

        return $configuration;
    }
}
