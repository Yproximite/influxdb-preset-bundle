<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

/**
 * Interface PointPresetFactoryInterface
 */
interface PointPresetFactoryInterface
{
    public function create(): PointPresetInterface;

    public function createFromConfig(array $config): PointPresetInterface;
}
