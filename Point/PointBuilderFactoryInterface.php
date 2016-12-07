<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

/**
 * Interface PointBuilderFactoryInterface
 */
interface PointBuilderFactoryInterface
{
    public function create(): PointBuilderInterface;
}
