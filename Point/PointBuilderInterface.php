<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

use InfluxDB\Point;

/**
 * Interface PointBuilderInterface
 */
interface PointBuilderInterface
{
    public function getPreset(): PointPresetInterface;

    public function setPreset(PointPresetInterface $preset): self;

    public function getValue(): float;

    public function setValue(float $value): self;

    public function build(): Point;
}
