<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener;

use Yproximite\Bundle\InfluxDbPresetBundle\Client\ClientInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Event\InfluxDbEvent;

final class InfluxDbListener
{
    public function __construct(private ClientInterface $client, private string $defaultProfileName)
    {
    }

    public function onInfluxDb(InfluxDbEvent $event, string $eventName): void
    {
        $profileName = $event->getProfileName() ?? $this->defaultProfileName;

        $this->client->sendPoint($profileName, $eventName, $event->getValue(), $event->getDateTime());
    }
}
