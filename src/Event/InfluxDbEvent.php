<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Event;

use Yproximite\InfluxDbBundle\Events\SymfonyEvent;

final class InfluxDbEvent extends SymfonyEvent
{
    /**
     * @var float
     */
    private $value;

    /**
     * @var string|null
     */
    private $profileName;

    /**
     * @var \DateTimeInterface|null
     */
    private $dateTime;

    /**
     * @param float|int $value
     */
    public function __construct($value, ?string $profileName = null, ?\DateTimeInterface $dateTime = null)
    {
        $this->value       = (float) $value;
        $this->profileName = $profileName;
        $this->dateTime    = $dateTime;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getProfileName(): ?string
    {
        return $this->profileName;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }
}
