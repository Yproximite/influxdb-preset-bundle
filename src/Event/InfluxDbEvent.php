<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Event;

final class InfluxDbEvent extends Event
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

    public function __construct($value, string $profileName = null, \DateTimeInterface $dateTime = null)
    {
        $this->value       = (float) $value;
        $this->profileName = $profileName;
        $this->dateTime    = $dateTime;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getProfileName()
    {
        return $this->profileName;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}
