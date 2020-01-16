<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Profile;

use Yproximite\Bundle\InfluxDbPresetBundle\Connection\ConnectionFactoryInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetFactoryInterface;

/**
 * Class ProfileFactory
 */
class ProfileFactory implements ProfileFactoryInterface
{
    /**
     * @var PointPresetFactoryInterface
     */
    private $pointPresetFactory;

    /**
     * @var ConnectionFactoryInterface
     */
    private $connectionFactory;

    public function __construct(
        PointPresetFactoryInterface $pointPresetFactory,
        ConnectionFactoryInterface $connectionFactory
    ) {
        $this->pointPresetFactory = $pointPresetFactory;
        $this->connectionFactory  = $connectionFactory;
    }

    public function create(): ProfileInterface
    {
        return new Profile();
    }

    public function createFromConfig(array $config): ProfileInterface
    {
        $profile = $this->create();
        $profile->setName($config['name']);

        foreach ($config['presets'] as $presetName => $presetConfig) {
            $preset = $this->pointPresetFactory->createFromConfig($presetConfig + ['name' => $presetName]);

            $profile->addPointPreset($preset);
        }

        foreach ($config['connections'] as $connectionName => $connectionConfig) {
            $connection = $this->connectionFactory->createFromConfig($connectionConfig + ['name' => $connectionName]);

            $profile->addConnection($connection);
        }

        return $profile;
    }
}
