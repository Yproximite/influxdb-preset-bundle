<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

interface PointPresetInterface
{
    public function getName(): string;

    public function setName(string $name): self;

    public function getMeasurement(): string;

    public function setMeasurement(string $measurement): self;

    /**
     * @return array<string, string>
     */
    public function getTags(): array;

    /**
     * @param array<string, string> $tags
     */
    public function setTags(array $tags): self;

    /**
     * @return array<string, string>
     */
    public function getFields(): array;

    /**
     * @param array<string, string> $fields
     */
    public function setFields(array $fields): self;
}
