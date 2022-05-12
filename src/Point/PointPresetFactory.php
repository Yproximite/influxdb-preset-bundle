<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

class PointPresetFactory implements PointPresetFactoryInterface
{
    public function create(): PointPresetInterface
    {
        return new PointPreset();
    }

    public function createFromConfig(array $config): PointPresetInterface
    {
        $preset = $this->create();
        $preset
            ->setName($config['name'])
            ->setMeasurement($config['measurement'])
            ->setTags($config['tags'])
            ->setFields($config['fields'])
        ;

        return $preset;
    }
}
