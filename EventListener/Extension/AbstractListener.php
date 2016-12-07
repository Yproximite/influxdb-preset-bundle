<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Yproximite\Bundle\InfluxDbPresetBundle\Event\InfluxDbEvent;

/**
 * Class AbstractListener
 */
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

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
    }

    protected function dispatchEvent($value)
    {
        $this->eventDispatcher->dispatch($this->eventName, new InfluxDbEvent($value));
    }
}
