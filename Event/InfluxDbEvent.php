<?php

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

    public function __construct($value)
    {
        $this->value = (float) $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
