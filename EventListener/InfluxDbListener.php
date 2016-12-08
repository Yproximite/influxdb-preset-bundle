<?php
declare(strict_types=1);

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

    /**
     * @var string
     */
    private $defaultProfileName;

    public function __construct(Client $client, string $defaultProfileName)
    {
        $this->client             = $client;
        $this->defaultProfileName = $defaultProfileName;
    }

    public function onInfluxDb(InfluxDbEvent $event, $eventName)
    {
        $profileName = $event->getProfileName() ?: $this->defaultProfileName;

        $this->client->sendPoint($profileName, $eventName, $event->getValue());
    }
}
