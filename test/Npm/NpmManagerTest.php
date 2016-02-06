<?php

namespace Hshn\NpmBundle\Npm;

use Prophecy\Argument;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Npm|\Prophecy\Prophecy\ObjectProphecy;
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
        $this->manager->install(['foo' => 'bar']);
        $this->npm->install(['foo' => 'bar'], Argument::type(ConfigurationInterface::class))->shouldBeCalledTimes(2);
    }

    /**
     * @test
     */
    public function testInstall()
    {
        $this->manager->run(['foo' => 'bar']);
        $this->npm->run(['foo' => 'bar'], Argument::type(ConfigurationInterface::class))->shouldBeCalledTimes(2);
    }

    /**
     * @test
     */
    public function testBundles()
    {
        $this->manager->bundles(['foo'])->run(['foo' => 'bar']);
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
