<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

interface PointPresetFactoryInterface
{
    public function create(): PointPresetInterface;

    /**
     * @param array{ name:string, measurement:string, tags:array<string, string>, fields:array<string,string> } $config
     */
    public function createFromConfig(array $config): PointPresetInterface;
}
