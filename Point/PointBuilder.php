<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

use InfluxDB\Point;

/**
 * Class PointBuilder
 */
class PointBuilder implements PointBuilderInterface
{
    /**
     * @var PointPresetInterface
     */
    private $preset;

    /**
     * @var float
     */
    private $value;

    public function getPreset(): PointPresetInterface
    {
        return $this->preset;
    }

    public function setPreset(PointPresetInterface $preset): self
    {
        $this->preset = $preset;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function build(): Point
    {
        $measurement = $this->getCompiledMeasurement();
        $value       = $this->getValue();
        $tags        = $this->getPreset()->getTags();
        $fields      = $this->getPreset()->getFields();
        $timestamp   = $this->getTimestamp();

        return new Point($measurement, $value, $tags, $fields, $timestamp);
    }

    private function getCompiledMeasurement(): string
    {
        $measurement = $this->getPreset()->getMeasurement();

        if (preg_match_all('/<([^>]*)>/', $measurement, $matches) > 0) {
            $params = $this->getMeasurementParams();
            $keys   = $matches[1];

            foreach ($keys as $key) {
                $measurement = str_replace(sprintf('<%s>', $key), $params[$key], $measurement);
            }
        }

        return $measurement;
    }

    private function getMeasurementParams(): array
    {
        return [
            'value' => $this->getValue(),
        ];
    }

    private function getTimestamp(): int
    {
        $date = new \DateTime();

        return $date->getTimestamp();
    }
}
