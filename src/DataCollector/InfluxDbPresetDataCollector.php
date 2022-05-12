<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Yproximite\Bundle\InfluxDbPresetBundle\Event\ClientRequestEvent;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfilePoolInterface;

final class InfluxDbPresetDataCollector extends DataCollector
{
    /**
     * @var ProfilePoolInterface
     */
    private $profilePool;

    /**
     * @var ClientRequest[]
     */
    private $requests = [];

    public function __construct(ProfilePoolInterface $profilePool)
    {
        $this->profilePool = $profilePool;
    }

    public function onClientRequest(ClientRequestEvent $event): void
    {
        $profile = $this->profilePool->getProfileByName($event->getProfileName());
        $preset  = $profile->getPointPresetByName($event->getPresetName());

        $this->requests[] = new ClientRequest($profile, $preset, $event->getValue(), $event->getDateTime());
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data = [
            'requests' => $this->requests,
        ];
    }

    /**
     * Resets this data collector to its initial state.
     */
    public function reset(): void
    {
        $this->data = [];
    }

    /**
     * @return ClientRequest[]
     */
    public function getRequests(): array
    {
        return $this->data['requests'];
    }

    public function getName(): string
    {
        return 'yproximite.influxdb_preset';
    }
}
