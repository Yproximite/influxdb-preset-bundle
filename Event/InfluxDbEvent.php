<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class InfluxDbEvent
 */
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

    public function __construct($value, string $profileName = null)
    {
        $this->value       = (float)$value;
        $this->profileName = $profileName;
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
}
