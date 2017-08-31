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

        $this->loadBinaries($config['bin'], $container);
        $this->loadBundles($config['bundles'], $this->getPackageManagerReference($config['package_manager']), $container);
    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    private function loadBinaries(array $configs, ContainerBuilder $container)
    {
        $container->getDefinition('hshn.package_manager.npm')->replaceArgument(0, $configs['npm']);
        $container->getDefinition('hshn.package_manager.yarn')->replaceArgument(0, $configs['yarn']);
    }

    /**
     * @param array            $bundles
     * @param Reference        $defaultPackageManager
     * @param ContainerBuilder $container
     */
    private function loadBundles(array $bundles, Reference $defaultPackageManager, ContainerBuilder $container)
    {
        $configurations = [];

        foreach ($bundles as $bundle => $config) {
            $path = $this->getBundleLocation($container, $bundle)->getPathname();

            $container
                ->setDefinition($configuration = "hshn.npm.config.${bundle}", new DefinitionDecorator('hshn.npm.config'))
                ->setArguments([
                    $bundle,
                    $path.'/'.$config['directory'],
                    $config['package_manager'] ? $this->getPackageManagerReference($config['package_manager']) : $defaultPackageManager,
                ])
                ->setPublic(false);

            $configurations[] = new Reference($configuration);
        }

        $container->getDefinition('hshn.npm.manager')->replaceArgument(0, $configurations);
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $bundle
     *
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

    /**
     * @param string $name
     *
     * @return Reference
     */
    private function getPackageManagerReference($name)
    {
        return new Reference(sprintf('hshn.package_manager.%s', $name));
    }
}
