<?php

namespace Hshn\NpmBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('hshn_npm');
        $root
            ->children()
                ->enumNode('package_manager')
                    ->values(['npm', 'yarn'])
                    ->defaultValue('npm')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('bin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('npm')->info('npm binary path')->defaultValue('npm')->end()
                        ->scalarNode('yarn')->info('yarn binary path')->defaultValue('yarn')->end()
                    ->end()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($npmBinPath) {
                            @trigger_error('The configuration "hshn_npm.bin" for "npm binary path" was deprecated, please use "hshn_npm.bin.npm" instead', E_USER_DEPRECATED);
                            return [
                                'npm' => $npmBinPath,
                            ];
                        })
                    ->end()
                ->end()
                ->arrayNode('bundles')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('directory')
                                ->cannotBeEmpty()
                                ->defaultValue('./Resources/npm')
                            ->end()
                            ->enumNode('package_manager')
                                ->values(['npm', 'yarn'])
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
