<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Client;

use InfluxDB\Point;
use InfluxDB\Database;
use Algatux\InfluxDbBundle\Events\UdpEvent;
use Algatux\InfluxDbBundle\Events\HttpEvent;
use Algatux\InfluxDbBundle\Events\DeferredUdpEvent;
use Algatux\InfluxDbBundle\Events\DeferredHttpEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Yproximite\Bundle\InfluxDbPresetBundle\Events;
use Yproximite\Bundle\InfluxDbPresetBundle\Event\ClientRequestEvent;
use Yproximite\Bundle\InfluxDbPresetBundle\Exception\LogicException;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfilePoolInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Connection\ConnectionInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointBuilderFactoryInterface;

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

    public function sendPoint(string $profileName, string $presetName, float $value)
    {
        $profile = $this->profilePool->getProfileByName($profileName);
        $preset  = $profile->getPointPresetByName($presetName);
        $point   = $this->buildPoint($preset, $value);

        foreach ($profile->getConnections() as $connection) {
            $this->sendPointUsingConnection($point, $connection);
        }

        $event = new ClientRequestEvent($profileName, $presetName, $value);

        $this->eventDispatcher->dispatch(Events::CLIENT_REQUEST, $event);
    }

    private function buildPoint(PointPresetInterface $preset, float $value): Point
    {
        $builder = $this->pointBuilderFactory->create();
        $builder
            ->setPreset($preset)
            ->setValue($value)
        ;

        return $builder->build();
    }

    private function sendPointUsingConnection(Point $point, ConnectionInterface $connection)
    {
        $class = $this->getEventClassName($connection);
        $event = new $class([$point], Database::PRECISION_SECONDS, $connection->getName());

        $this->eventDispatcher->dispatch(constant(sprintf('%s::NAME', $class)), $event);
    }

    private function getEventClassName(ConnectionInterface $connection): string
    {
        switch (true) {
            case ($connection->isHttpProtocol() && !$connection->isDeferred()):
                return HttpEvent::class;
            case ($connection->isHttpProtocol() && $connection->isDeferred()):
                return DeferredHttpEvent::class;
            case ($connection->isUdpProtocol() && !$connection->isDeferred()):
                return UdpEvent::class;
            case ($connection->isUdpProtocol() && $connection->isDeferred()):
                return DeferredUdpEvent::class;
            default:
                throw new LogicException('Could not determine the event class name.');
        }
    }
}
