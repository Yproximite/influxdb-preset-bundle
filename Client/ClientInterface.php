<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Client;

use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfileInterface;

/**
 * Interface ClientInterface
 */
interface ClientInterface
{
    public function sendPoint(string $profileName, string $presetName, float $value);
}
