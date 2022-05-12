<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Client;

interface ClientInterface
{
    public function sendPoint(
        string $profileName,
        string $presetName,
        float $value,
        ?\DateTimeInterface $dateTime = null
    ): void;
}
