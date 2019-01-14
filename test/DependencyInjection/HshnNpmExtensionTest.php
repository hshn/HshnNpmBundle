<?php

namespace Hshn\NpmBundle\DependencyInjection;

use Hshn\NpmBundle\DependencyInjection\Fixture\FooBundle;
use Hshn\NpmBundle\Functional\Bundle\BarBundle\BarBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;


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
                'FooBundle' => null,
            ],
        ]);

        $this->assertEquals(['npm'], $container->getDefinition('hshn.package_manager.npm')->getArguments());

        $definition = $container->getDefinition('hshn.npm.config.foobundle');
        $this->assertEquals('FooBundle', $definition->getArgument(0));
        $this->assertStringEndsWith('/Fixture/./Resources/npm', $definition->getArgument(1));
        $this->assertEquals(new Reference('hshn.package_manager.npm'), $definition->getArgument(2));

        self::assertEquals(['npm'], $container->getDefinition('hshn.package_manager.npm')->getArguments());
        self::assertEquals(['yarn'], $container->getDefinition('hshn.package_manager.yarn')->getArguments());
    }

    /**
     * @test
     */
    public function test()
    {
        $container = $this->load([
            'bin'     => [
                'npm' => '/usr/local/bin/npm',
            ],
            'bundles' => [
                'FooBundle' => ['directory' => '..'],
            ],
        ]);

        $definition = $container->getDefinition('hshn.npm.config.foobundle');
        $this->assertEquals('FooBundle', $definition->getArgument(0));
        $this->assertStringEndsWith('/Fixture/..', $definition->getArgument(1));
    }

    /**
     * @test
     * @expectedDeprecation The configuration "hshn_npm.bin" for "npm binary path" was deprecated
     */
    public function testBinariesLegacy()
    {
        $container = $this->load([
            'bin'     => '/usr/local/bin/npm',
            'bundles' => [],
        ]);

        self::assertEquals(['/usr/local/bin/npm'], $container->getDefinition('hshn.package_manager.npm')->getArguments());
        self::assertEquals(['yarn'], $container->getDefinition('hshn.package_manager.yarn')->getArguments());
    }

    /**
     * @test
     */
    public function testBinaries()
    {
        $container = $this->load([
            'bin'     => [
                'npm' => '/usr/local/bin/npm',
                'yarn' => '/usr/local/bin/yarn',
            ],
            'bundles' => [],
        ]);

        self::assertEquals(['/usr/local/bin/npm'], $container->getDefinition('hshn.package_manager.npm')->getArguments());
        self::assertEquals(['/usr/local/bin/yarn'], $container->getDefinition('hshn.package_manager.yarn')->getArguments());
    }

    public function testPackageManager()
    {
        $container = $this->load([
            'package_manager' => 'yarn',
            'bundles' => [
                'FooBundle' => ['directory' => '..'],
                'BarBundle' => ['directory' => '..', 'package_manager' => 'npm'],
            ],
        ]);

        $definition = $container->getDefinition('hshn.npm.config.foobundle');
        self::assertEquals(new Reference('hshn.package_manager.yarn'), $definition->getArgument(2), 'global package manager');

        $definition = $container->getDefinition('hshn.npm.config.barbundle');
        self::assertEquals(new Reference('hshn.package_manager.npm'), $definition->getArgument(2), 'override global package manager');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp "BazBundle"
     */
    public function testThrowExceptionUnlessValidBundles()
    {
        $this->load([
            'bin'     => [
                'npm' => '/usr/local/bin/npm',
            ],
            'bundles' => [
                'FooBundle' => null,
                'BazBundle' => null,
            ],
        ]);
    }

    /**
     * @param array $config
     *
     * @return ContainerBuilder
     */
    private function load(array $config)
    {
        $extension = new HshnNpmExtension();
        $extension->load(['hshn_npm' => $config], $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FooBundle' => FooBundle::class,
                'BarBundle' => BarBundle::class,
            ],
        ])));

        return $container;
    }
}
