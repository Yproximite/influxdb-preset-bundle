<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener;

use Yproximite\Bundle\InfluxDbPresetBundle\Event\InfluxDbEvent;
use Yproximite\Bundle\InfluxDbPresetBundle\Client\ClientInterface;

/**
 * Class InfluxDbListener
 */
final class InfluxDbListener
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $defaultProfileName;

    public function __construct(ClientInterface $client, string $defaultProfileName)
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
