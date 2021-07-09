<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('metro_markets_feature_flag_bundle');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('cache')
                    ->children()
                        ->scalarNode('driver')->end()
                        ->scalarNode('ttl')->end()
                    ->end()
                ->end()
                ->scalarNode('logger')->end()
                ->scalarNode('provider')->defaultValue('null')->end()
                ->arrayNode('configcat')
                    ->children()
                        ->scalarNode('sdk_key')->end()
                        ->arrayNode('cache')
                            ->children()
                                ->scalarNode('driver')->end()
                                ->scalarNode('ttl')->end()
                            ->end()
                        ->end()
                        ->scalarNode('logger')->end()
                    ->end()
                ->end()
                ->arrayNode('rest')
                    ->children()
                        ->scalarNode('endpoint')->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
