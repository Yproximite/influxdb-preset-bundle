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

use Yproximite\Bundle\InfluxDbPresetBundle\Exception\LogicException;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfileInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Connection\ConnectionInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfileFactoryInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Exception\ProfileNotFoundException;
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
     * @var ProfileFactoryInterface
     */
    private $profileFactory;

    /**
     * @var PointBuilderFactoryInterface
     */
    private $pointBuilderFactory;

    /**
     * @var ProfileInterface[]
     */
    private $profiles = [];

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ProfileFactoryInterface $profileFactory,
        PointBuilderFactoryInterface $pointBuilderFactory
    ) {
        $this->eventDispatcher     = $eventDispatcher;
        $this->profileFactory      = $profileFactory;
        $this->pointBuilderFactory = $pointBuilderFactory;
    }

    public function addProfile(ProfileInterface $profile): ClientInterface
    {
        $this->profiles[] = $profile;

        return $this;
    }

    public function addProfileFromConfig(array $config): ClientInterface
    {
        $profile = $this->profileFactory->createFromConfig($config);

        return $this->addProfile($profile);
    }

    public function sendPoint(string $profileName, string $presetName, float $value)
    {
        $profile = $this->getProfileByName($profileName);
        $preset  = $profile->getPointPresetByName($presetName);
        $point   = $this->buildPoint($preset, $value);

        foreach ($profile->getConnections() as $connection) {
            $this->sendPointUsingConnection($point, $connection);
        }
    }

    private function getProfileByName(string $profileName): ProfileInterface
    {
        foreach ($this->profiles as $profile) {
            if ($profile->getName() === $profileName) {
                return $profile;
            }
        }

        throw new ProfileNotFoundException(sprintf('Could not find the profile "%s".', $profileName));
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
            case ($connection->isHttpProtocol() && !$connection->isDeffered()):
                return HttpEvent::class;
            case ($connection->isHttpProtocol() && $connection->isDeffered()):
                return DeferredHttpEvent::class;
            case ($connection->isUdpProtocol() && !$connection->isDeffered()):
                return UdpEvent::class;
            case ($connection->isUdpProtocol() && $connection->isDeffered()):
                return DeferredUdpEvent::class;
            default:
                throw new LogicException('Could not determine the event class name.');
        }
    }
}
