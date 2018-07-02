<?php
declare(strict_types=1);

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

    /**
     * @var string
     */
    protected $profileName;

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
    }

    public function setProfileName(string $profileName)
    {
        $this->profileName = $profileName;
    }

    protected function dispatchEvent($value)
    {
        $this->eventDispatcher->dispatch($this->eventName, new InfluxDbEvent($value, $this->profileName));
    }
}
