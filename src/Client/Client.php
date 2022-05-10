<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Client;

use Algatux\InfluxDbBundle\Events\DeferredHttpEvent;
use Algatux\InfluxDbBundle\Events\DeferredUdpEvent;
use Algatux\InfluxDbBundle\Events\HttpEvent;
use Algatux\InfluxDbBundle\Events\UdpEvent;
use InfluxDB\Database;
use InfluxDB\Point;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Connection\ConnectionInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Event\ClientRequestEvent;
use Yproximite\Bundle\InfluxDbPresetBundle\Events;
use Yproximite\Bundle\InfluxDbPresetBundle\Exception\LogicException;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointBuilderFactoryInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfilePoolInterface;

/**
 * Class Client
 */
class Client implements ClientInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ProfilePoolInterface
     */
    private $profilePool;

    /**
     * @var PointBuilderFactoryInterface
     */
    private $pointBuilderFactory;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ProfilePoolInterface $profilePool,
        PointBuilderFactoryInterface $pointBuilderFactory
    ) {
        $this->eventDispatcher     = $eventDispatcher;
        $this->profilePool         = $profilePool;
        $this->pointBuilderFactory = $pointBuilderFactory;
    }

    public function sendPoint(
        string $profileName,
        string $presetName,
        float $value,
        \DateTimeInterface $dateTime = null
    ) {
        if (!$dateTime) {
            $dateTime = new \DateTime();
        }

        $profile = $this->profilePool->getProfileByName($profileName);
        $preset  = $profile->getPointPresetByName($presetName);
        $point   = $this->buildPoint($preset, $value, $dateTime);

        foreach ($profile->getConnections() as $connection) {
            $this->sendPointUsingConnection($point, $connection);
        }

        $event = new ClientRequestEvent($profileName, $presetName, $value, $dateTime);
        $this->eventDispatcher->dispatch($event, Events::CLIENT_REQUEST);
    }

    private function buildPoint(PointPresetInterface $preset, float $value, \DateTimeInterface $dateTime): Point
    {
        $builder = $this->pointBuilderFactory->create();
        $builder
            ->setPreset($preset)
            ->setValue($value)
            ->setDateTime($dateTime)
        ;

        return $builder->build();
    }

    private function sendPointUsingConnection(Point $point, ConnectionInterface $connection)
    {
        $class = $this->getEventClassName($connection);
        $event = new $class([$point], Database::PRECISION_SECONDS, $connection->getName());

        $this->eventDispatcher->dispatch($event, \constant(sprintf('%s::NAME', $class)));
    }

    private function getEventClassName(ConnectionInterface $connection): string
    {
        switch (true) {
            case $connection->isHttpProtocol() && !$connection->isDeferred():
                return HttpEvent::class;
            case $connection->isHttpProtocol() && $connection->isDeferred():
                return DeferredHttpEvent::class;
            case $connection->isUdpProtocol() && !$connection->isDeferred():
                return UdpEvent::class;
            case $connection->isUdpProtocol() && $connection->isDeferred():
                return DeferredUdpEvent::class;
            default:
                throw new LogicException('Could not determine the event class name.');
        }
    }
}
