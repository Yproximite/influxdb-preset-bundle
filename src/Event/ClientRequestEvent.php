<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Event;

use Algatux\InfluxDbBundle\Events\SymfonyEvent;

final class ClientRequestEvent extends SymfonyEvent
{
    /**
     * @var string
     */
    private $profileName;

    /**
     * @var string
     */
    private $presetName;

    /**
     * @var float
     */
    private $value;

    /**
     * @var \DateTimeInterface
     */
    private $dateTime;

    public function __construct(string $profileName, string $presetName, float $value, \DateTimeInterface $dateTime)
    {
        $this->profileName = $profileName;
        $this->presetName  = $presetName;
        $this->value       = $value;
        $this->dateTime    = $dateTime;
    }

    public function getProfileName(): string
    {
        return $this->profileName;
    }

    public function getPresetName(): string
    {
        return $this->presetName;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }
}
