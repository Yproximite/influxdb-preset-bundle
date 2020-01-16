<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Profile;

use Yproximite\Bundle\InfluxDbPresetBundle\Connection\ConnectionInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Exception\PresetNotFoundException;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;

/**
 * Class Profile
 */
class Profile implements ProfileInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var PointPresetInterface[]
     */
    private $pointPresets = [];

    /**
     * @var ConnectionInterface[]
     */
    private $connections = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProfileInterface
    {
        $this->name = $name;

        return $this;
    }

    public function addPointPreset(PointPresetInterface $preset): ProfileInterface
    {
        $this->pointPresets[] = $preset;

        return $this;
    }

    public function getPointPresetByName(string $presetName): PointPresetInterface
    {
        foreach ($this->pointPresets as $pointPreset) {
            if ($pointPreset->getName() === $presetName) {
                return $pointPreset;
            }
        }

        throw new PresetNotFoundException(sprintf('Could not find the preset "%s".', $presetName));
    }

    public function getPointPresets(): array
    {
        return $this->pointPresets;
    }

    public function addConnection(ConnectionInterface $connection): ProfileInterface
    {
        $this->connections[] = $connection;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConnections(): array
    {
        return $this->connections;
    }
}
