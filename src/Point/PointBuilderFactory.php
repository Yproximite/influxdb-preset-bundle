<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

/**
 * Class PointBuilderFactory
 */
class PointBuilderFactory implements PointBuilderFactoryInterface
{
    public function create(): PointBuilderInterface
    {
        return new PointBuilder();
    }
}
