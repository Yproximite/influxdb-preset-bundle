<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

interface PointBuilderFactoryInterface
{
    public function create(): PointBuilderInterface;
}
