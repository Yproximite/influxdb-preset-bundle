<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Client;

use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;

/**
 * Interface ClientInterface
 */
interface ClientInterface
{
    public function addPointPreset(PointPresetInterface $preset);

    public function addPointPresetFromConfig(array $config);

    public function sendPoint(string $presetName, float $value);

    public function sendDeferredPoint(string $presetName, float $value);
}
