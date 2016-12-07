<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class YproximiteInfluxDbPresetExtension
 */
class YproximiteInfluxDbPresetExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();
        $config        = $processor->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $clientDefinition   = $container->getDefinition('yproximite.influx_db_preset.client.client');
        $influxDbDefinition = $container->getDefinition('yproximite.influx_db_preset.event_listener.influx_db');

        foreach ($config['presets'] as $presetConfig) {
            $clientDefinition->addMethodCall('addPointPresetFromConfig', [$presetConfig]);

            $influxDbDefinition->addTag(
                'kernel.event_listener',
                ['event' => $presetConfig['name'], 'method' => 'onInfluxDb']
            );
        }

        foreach ($config['extensions'] as $extensionName => $extension) {
            if ($extension['enabled']) {
                $loader->load(sprintf('%s.yml', $extensionName));

                $extensionDefinitionId = sprintf(
                    'yproximite.influx_db_preset.event_listener.extension.%s',
                    $extensionName
                );

                $extensionDefinition = $container->getDefinition($extensionDefinitionId);
                $extensionDefinition->addMethodCall('setEventName', [$extension['preset_name']]);
            }
        }
    }
}
