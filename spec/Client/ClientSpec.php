<?php

declare(strict_types=1);

namespace spec\Yproximite\Bundle\InfluxDbPresetBundle\Client;

use Yproximite\InfluxDbBundle\Events\DeferredHttpEvent;
use Yproximite\InfluxDbBundle\Events\DeferredUdpEvent;
use Yproximite\InfluxDbBundle\Events\HttpEvent;
use Yproximite\InfluxDbBundle\Events\UdpEvent;
use InfluxDB\Database;
use InfluxDB\Point;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Client\Client;
use Yproximite\Bundle\InfluxDbPresetBundle\Connection\Connection;
use Yproximite\Bundle\InfluxDbPresetBundle\Event\ClientRequestEvent;
use Yproximite\Bundle\InfluxDbPresetBundle\Events;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointBuilder;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointBuilderFactoryInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPreset;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\Profile;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfilePoolInterface;

class ClientSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    public function let(
        EventDispatcherInterface $eventDispatcher,
        ProfilePoolInterface $profilePool,
        PointBuilderFactoryInterface $pointBuilderFactory
    ) {
        $profilePool->getProfileByName('default')->willReturn($this->buildDefaultProfile());
        $profilePool->getProfileByName('custom')->willReturn($this->buildCustomProfile());

        $pointBuilderFactory->create()->willReturn(new PointBuilder());

        $this->beConstructedWith($eventDispatcher, $profilePool, $pointBuilderFactory);
    }

    public function it_should_send_point_through_each_of_connection_types(EventDispatcherInterface $eventDispatcher)
    {
        $dateTime = new \DateTime();


        $point = new Point('apples', 7., [], [], $dateTime->getTimestamp());

        $connectionEventClassMap = [
            'alpha' => HttpEvent::class,
            'beta'  => DeferredHttpEvent::class,
            'gamma' => UdpEvent::class,
            'delta' => DeferredUdpEvent::class,
        ];

        foreach ($connectionEventClassMap as $connectionName => $eventClass) {
            $event = new $eventClass([$point], Database::PRECISION_SECONDS, $connectionName);
            $eventDispatcher->dispatch($event, constant(sprintf('%s::NAME', $eventClass)))->willReturn($event)->shouldBeCalled();
        }

        $event = new ClientRequestEvent('default', 'apple.dropped', 7., $dateTime);
        $eventDispatcher->dispatch($event, Events::CLIENT_REQUEST)->willReturn($event)->shouldBeCalled();

        $this->sendPoint('default', 'apple.dropped', 7., $dateTime);
    }

    public function it_should_use_other_profiles_separately(EventDispatcherInterface $eventDispatcher)
    {
        $dateTime = new \DateTime();

        $point = new Point('custom_apples', 5., [], [], $dateTime->getTimestamp());

        $event = new DeferredUdpEvent([$point], Database::PRECISION_SECONDS, 'alpha');
        $eventDispatcher->dispatch($event, DeferredUdpEvent::NAME)->willReturn($event)->shouldBeCalled();

        $event = new ClientRequestEvent('custom', 'apple.dropped', 5., $dateTime);
        $eventDispatcher->dispatch($event, Events::CLIENT_REQUEST)->willReturn($event)->shouldBeCalled();

        $this->sendPoint('custom', 'apple.dropped', 5., $dateTime);
    }

    private function buildDefaultProfile(): Profile
    {
        $pointPreset = new PointPreset();
        $pointPreset
            ->setName('apple.dropped')
            ->setMeasurement('apples')
        ;

        $profile = new Profile();
        $profile
            ->setName('default')
            ->addPointPreset($pointPreset)
        ;

        $connectionConfigs = [
            ['alpha', 'http', false],
            ['beta', 'http', true],
            ['gamma', 'udp', false],
            ['delta', 'udp', true],
        ];

        foreach ($connectionConfigs as $connectionConfig) {
            [$connectionName, $connectionProtocol, $connectionDeferred] = $connectionConfig;

            $connection = new Connection();
            $connection
                ->setName($connectionName)
                ->setProtocol($connectionProtocol)
                ->setDeferred($connectionDeferred)
            ;

            $profile->addConnection($connection);
        }

        return $profile;
    }

    private function buildCustomProfile(): Profile
    {
        $pointPreset = new PointPreset();
        $pointPreset
            ->setName('apple.dropped')
            ->setMeasurement('custom_apples')
        ;

        $connection = new Connection();
        $connection
            ->setName('alpha')
            ->setProtocol('udp')
            ->setDeferred(true)
        ;

        $profile = new Profile();
        $profile
            ->setName('custom')
            ->addConnection($connection)
            ->addPointPreset($pointPreset)
        ;

        return $profile;
    }
}
