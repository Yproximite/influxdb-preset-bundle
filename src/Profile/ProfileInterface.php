<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Profile;

use Yproximite\Bundle\InfluxDbPresetBundle\Connection\ConnectionInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;

/**
 * Interface ProfileInterface
 */
interface ProfileInterface
{
    public function getName(): string;

    public function setName(string $name): self;

    public function addPointPreset(PointPresetInterface $preset): self;

    public function getPointPresetByName(string $presetName): PointPresetInterface;

    public function getPointPresets(): array;

    public function addConnection(ConnectionInterface $connection): self;

    /**
     * @return ConnectionInterface[]
     */
    public function getConnections(): array;
}
