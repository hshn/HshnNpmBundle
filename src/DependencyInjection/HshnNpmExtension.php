<?php

namespace Hshn\NpmBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class HshnNpmExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('npm.yml');

        $container->setParameter('hshn.npm.bin', $config['bin']);

        $this->loadBundles($config['bundles'], $container);
    }

    /**
     * @param array $bundles
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function loadBundles(array $bundles, ContainerBuilder $container)
    {
        $configurations = [];

        foreach ($bundles as $bundle => $config) {
            $path = $this->getBundleLocation($container, $bundle)->getPathname();

            $container
                ->setDefinition($configuration = "hshn.npm.config.${bundle}", new DefinitionDecorator('hshn.npm.config'))
                ->setArguments([$bundle, $path.'/'.$config['directory']])
                ->setPublic(false);

            $configurations[] = new Reference($configuration);
        }

        $container->getDefinition('hshn.npm.manager')->replaceArgument(1, $configurations);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $bundle
     * @return \SplFileInfo
     */
    private function getBundleLocation(ContainerBuilder $container, $bundle)
    {
        foreach ($container->getParameter('kernel.bundles') as $name => $class) {
            if ($bundle === $name) {
                $path = (new \ReflectionClass($class))->getFileName();

                return new \SplFileInfo(dirname($path));
            }
        }

        throw new \InvalidArgumentException(sprintf('Unknown bundle "%s" was given', $bundle));
    }
}
