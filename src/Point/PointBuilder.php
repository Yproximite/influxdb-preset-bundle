<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Point;

use InfluxDB\Point;

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

    /**
     * @var \DateTimeInterface
     */
    private $dateTime;

    public function getPreset(): PointPresetInterface
    {
        return $this->preset;
    }

    public function setPreset(PointPresetInterface $preset): PointBuilderInterface
    {
        $this->preset = $preset;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): PointBuilderInterface
    {
        $this->value = $value;

        return $this;
    }

    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): PointBuilderInterface
    {
        $this->dateTime = $dateTime;

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
        $timestamp   = $this->getDateTime()->getTimestamp();

        return new Point($measurement, $value, $tags, $fields, $timestamp);
    }

    /**
     * @param string|array<string> $template
     *
     * @return array|string|string[]
     */
    private function compileTemplate($template)
    {
        if (\is_string($template) && preg_match_all('/<([^>]*)>/', $template, $matches) > 0) {
            $params = $this->getTemplateParams();
            $keys   = $matches[1];

            foreach ($keys as $key) {
                $template = str_replace(sprintf('<%s>', $key), (string) $params[$key], $template);
            }

            return $template;
        }

        return $template;
    }

    /**
     * @return array{ value:float }
     */
    private function getTemplateParams(): array
    {
        return [
            'value' => $this->getValue(),
        ];
    }
}
