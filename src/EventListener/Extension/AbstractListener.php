<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Event\InfluxDbEvent;

abstract class AbstractListener
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var string
     */
    protected $eventName;

    /**
     * @var string
     */
    protected $profileName;

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setEventName(string $eventName): void
    {
        $this->eventName = $eventName;
    }

    public function setProfileName(string $profileName): void
    {
        $this->profileName = $profileName;
    }

    /**
     * @param float $value
     */
    protected function dispatchEvent($value): void
    {
        $this->eventDispatcher->dispatch(new InfluxDbEvent($value, $this->profileName), $this->eventName);
    }
}
