<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('yproximite_influx_db_preset');

        $this->addPresetsSection($rootNode);
        $this->addExtensionsSection($rootNode);

        return $treeBuilder;
    }

    private function addPresetsSection(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('presets')
                    ->useAttributeAsKey('name', false)
                    ->prototype('array')
                        ->children()
                            ->scalarNode('measurement')->isRequired()->end()
                            ->arrayNode('tags')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('fields')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addExtensionsSection(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('extensions')
                    ->isRequired()
                    ->children()
                        ->arrayNode('exception')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('preset_name')->isRequired()->end()
                            ->end()
                        ->end()
                        ->arrayNode('memory')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('preset_name')->isRequired()->end()
                            ->end()
                        ->end()
                        ->arrayNode('request')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('preset_name')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
