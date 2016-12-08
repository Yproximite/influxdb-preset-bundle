<?php
declare(strict_types=1);

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
        $mapCallback = function ($value) {
            return $this->compileTemplate($value);
        };

        $measurement = $this->getPreset()->getMeasurement();
        $value       = $this->getValue();
        $tags        = array_map($mapCallback, $this->getPreset()->getTags());
        $fields      = array_map($mapCallback, $this->getPreset()->getFields());
        $timestamp   = $this->getTimestamp();

        return new Point($measurement, $value, $tags, $fields, $timestamp);
    }

    private function compileTemplate($template): string
    {
        $template = (string) $template;

        if (preg_match_all('/<([^>]*)>/', $template, $matches) > 0) {
            $params = $this->getTemplateParams();
            $keys   = $matches[1];

            foreach ($keys as $key) {
                $template = str_replace(sprintf('<%s>', $key), $params[$key], $template);
            }
        }

        return $template;
    }

    private function getTemplateParams(): array
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
