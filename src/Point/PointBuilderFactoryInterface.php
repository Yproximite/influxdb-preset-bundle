<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

/**
 * Interface PointBuilderFactoryInterface
 */
interface PointBuilderFactoryInterface
{
    public function create(): PointBuilderInterface;
}
