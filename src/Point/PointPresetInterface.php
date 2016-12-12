<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

/**
 * Interface PointPresetInterface
 */
interface PointPresetInterface
{
    public function getName(): string;

    public function setName(string $name): self;

    public function getMeasurement(): string;

    public function setMeasurement(string $measurement): self;

    public function getTags(): array;

    public function setTags(array $tags): self;

    public function getFields(): array;

    public function setFields(array $fields): self;
}
