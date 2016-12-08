<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Profile;

use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetFactoryInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Connection\ConnectionFactoryInterface;

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

        foreach ($config['presets'] as $presetConfig) {
            $preset = $this->pointPresetFactory->createFromConfig($presetConfig);

            $profile->addPointPreset($preset);
        }

        foreach ($config['connections'] as $connectionConfig) {
            $connection = $this->connectionFactory->createFromConfig($connectionConfig);

            $profile->addConnection($connection);
        }

        return $profile;
    }
}