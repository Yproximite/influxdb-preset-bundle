<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    private static $protocols = ['http', 'udp'];

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('yproximite_influx_db_preset');

        $this->addProfilesSection($rootNode);
        $this->addExtensionsSection($rootNode);

        return $treeBuilder;
    }

    private function addProfilesSection(NodeDefinition $rootNode)
    {
        $profiles = $rootNode
            ->children()
                ->scalarNode('default_profile_name')->defaultValue('default')->end()
                ->arrayNode('profiles')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array');
                        $this->addConnectionsSection($profiles);
                        $this->addPresetsSection($profiles);
                    $profiles->end()
                ->end()
            ->end()
        ;
    }

    private function addConnectionsSection(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('connections')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('protocol')
                                ->isRequired()
                                ->validate()
                                    ->ifTrue(function ($value) {
                                        return !in_array($value, self::$protocols);
                                    })
                                    ->thenInvalid(
                                        sprintf(
                                            'protocol parameter should be one of following: %s',
                                            implode(', ', self::$protocols)
                                        )
                                    )
                                ->end()
                            ->end()
                            ->booleanNode('deferred')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addPresetsSection(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('presets')
                    ->useAttributeAsKey('name')
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
                    ->children()
                        ->arrayNode('exception')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('preset_name')->isRequired()->end()
                                ->scalarNode('profile_name')->end()
                            ->end()
                        ->end()
                        ->arrayNode('memory')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('preset_name')->isRequired()->end()
                                ->scalarNode('profile_name')->end()
                            ->end()
                        ->end()
                        ->arrayNode('response_time')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('preset_name')->isRequired()->end()
                                ->scalarNode('profile_name')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
