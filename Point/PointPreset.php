<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

/**
 * Class PointPreset
 */
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
     * @var array
     */
    private $tags = [];

    /**
     * @var array
     */
    private $fields = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
    }

    public function getMeasurement(): string
    {
        return $this->measurement;
    }

    public function setMeasurement(string $measurement): self
    {
        $this->measurement = $measurement;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }
}
