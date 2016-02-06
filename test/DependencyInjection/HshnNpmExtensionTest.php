<?php

namespace Hshn\NpmBundle\DependencyInjection;

use Hshn\NpmBundle\DependencyInjection\Fixture\FooBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class HshnNpmExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testDefaultValues()
    {
        $container = $this->load([
            'bundles' => [
                'FooBundle' => null
            ]
        ]);

        $this->assertEquals('/usr/bin/npm', $container->getParameter('hshn.npm.bin'));

        $definition = $container->getDefinition('hshn.npm.config.foobundle');
        $this->assertEquals('FooBundle', $definition->getArgument(0));
        $this->assertStringEndsWith('/Fixture/./Resources/npm', $definition->getArgument(1));
    }

    /**
     * @test
     */
    public function test()
    {
        $container = $this->load([
            'bin' => '/usr/local/bin/npm',
            'bundles' => [
                'FooBundle' => ['directory' => '..']
            ]
        ]);

        $this->assertEquals('/usr/local/bin/npm', $container->getParameter('hshn.npm.bin'));

        $definition = $container->getDefinition('hshn.npm.config.foobundle');
        $this->assertEquals('FooBundle', $definition->getArgument(0));
        $this->assertStringEndsWith('/Fixture/..', $definition->getArgument(1));
    }

    /**
     * @test
     */
    public function testThrowExceptionUnlessValidBundles()
    {
        $this->setExpectedExceptionRegExp(\InvalidArgumentException::class, '"BarBundle"');
        $this->load([
            'bin' => '/usr/local/bin/npm',
            'bundles' => [
                'FooBundle' => null,
                'BarBundle' => null
            ]
        ]);
    }

    /**
     * @param array $config
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private function load(array $config)
    {
        $extension = new HshnNpmExtension();
        $extension->load(['hshn_npm' => $config], $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FooBundle' => FooBundle::class,
            ]
        ])));

        return $container;
    }
}
