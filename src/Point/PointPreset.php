<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

class PointPreset implements PointPresetInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $measurement;

    /**
     * @var array<string, string>
     */
    private $tags = [];

    /**
     * @var array<string, string>
     */
    private $fields = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PointPresetInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getMeasurement(): string
    {
        return $this->measurement;
    }

    public function setMeasurement(string $measurement): PointPresetInterface
    {
        $this->measurement = $measurement;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): PointPresetInterface
    {
        $this->tags = $tags;

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): PointPresetInterface
    {
        $this->fields = $fields;

        return $this;
    }
}
