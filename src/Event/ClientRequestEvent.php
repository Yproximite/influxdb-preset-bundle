<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ClientRequestEvent
 */
final class ClientRequestEvent extends Event
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

    public function __construct(string $profileName, string $presetName, float $value)
    {
        $this->profileName = $profileName;
        $this->presetName  = $presetName;
        $this->value       = $value;
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
}
