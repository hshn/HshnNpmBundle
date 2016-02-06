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
                ->scalarNode('bin')
                    ->info('npm binary path')
                    ->defaultValue('/usr/bin/npm')
                ->end()
                ->arrayNode('bundles')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('directory')
                                ->cannotBeEmpty()
                                ->defaultValue('./Resources/npm')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
