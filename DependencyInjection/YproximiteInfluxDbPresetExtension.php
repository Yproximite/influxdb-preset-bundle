<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Behat\Gherkin\Loader\FileLoaderInterface;
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

        $this->registerProfiles($config, $container);
        $this->registerEvents($config, $container);
        $this->registerExtensions($config, $container, $loader);

        $container->setParameter('yproximite.influx_db_preset.default_profile_name', $config['default_profile_name']);
    }

    private function registerProfiles(array $config, ContainerBuilder $container)
    {
        $definition = $container->getDefinition('yproximite.influx_db_preset.client.client');

        foreach ($config['profiles'] as $profileConfig) {
            $definition->addMethodCall('addProfileFromConfig', [$profileConfig]);
        }
    }

    private function registerEvents(array $config, ContainerBuilder $container)
    {
        $eventNames = [];
        $definition = $container->getDefinition('yproximite.influx_db_preset.event_listener.influx_db');

        foreach ($config['profiles'] as $profileConfig) {
            foreach ($profileConfig['presets'] as $presetConfig) {
                if (!array_key_exists($presetConfig['name'], $eventNames)) {
                    $eventNames[] = $presetConfig['name'];
                }
            }
        }

        foreach ($eventNames as $eventName) {
            $definition->addTag('kernel.event_listener', ['event' => $eventName, 'method' => 'onInfluxDb']);
        }
    }

    private function registerExtensions(array $config, ContainerBuilder $container, FileLoaderInterface $loader)
    {
        foreach ($config['extensions'] as $extensionName => $extension) {
            if ($extension['enabled']) {
                $loader->load(sprintf('extension/%s.yml', $extensionName));

                $extensionDefinitionId = sprintf(
                    'yproximite.influx_db_preset.event_listener.extension.%s',
                    $extensionName
                );

                $profileName = array_key_exists('profile_name', $extension)
                    ? $extension['profile_name']
                    : $config['default_profile_name']
                ;

                $extensionDefinition = $container->getDefinition($extensionDefinitionId);
                $extensionDefinition->addMethodCall('setEventName', [$extension['preset_name']]);
                $extensionDefinition->addMethodCall('setProfileName', [$profileName]);
            }
        }
    }
}
