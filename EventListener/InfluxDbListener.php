<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener;

use Yproximite\Bundle\InfluxDbPresetBundle\Client\Client;
use Yproximite\Bundle\InfluxDbPresetBundle\Event\InfluxDbEvent;

/**
 * Class InfluxDbListener
 */
final class InfluxDbListener
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function onInfluxDb(InfluxDbEvent $event, $eventName)
    {
        $this->client->sendDeferredPoint($eventName, $event->getValue());
    }
}
