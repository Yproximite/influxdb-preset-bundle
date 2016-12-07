<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\Client;

use InfluxDB\Point;
use InfluxDB\Database;
use Algatux\InfluxDbBundle\Events\UdpEvent;
use Algatux\InfluxDbBundle\Events\DeferredUdpEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Exception\PresetNotFoundException;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetFactoryInterface;
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
     * @var PointPresetFactoryInterface
     */
    private $pointPresetFactory;

    /**
     * @var PointBuilderFactoryInterface
     */
    private $pointBuilderFactory;

    /**
     * @var PointPresetInterface[]
     */
    private $pointPresets = [];

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        PointPresetFactoryInterface $pointPresetFactory,
        PointBuilderFactoryInterface $pointBuilderFactory
    ) {
        $this->eventDispatcher     = $eventDispatcher;
        $this->pointPresetFactory  = $pointPresetFactory;
        $this->pointBuilderFactory = $pointBuilderFactory;
    }

    public function addPointPreset(PointPresetInterface $preset)
    {
        $this->pointPresets[] = $preset;
    }

    public function addPointPresetFromConfig(array $config)
    {
        $preset = $this->pointPresetFactory->createFromConfig($config);

        $this->addPointPreset($preset);
    }

    public function sendPoint(string $presetName, float $value)
    {
        $point = $this->buildPoint($presetName, $value);
        $event = new UdpEvent([$point], Database::PRECISION_SECONDS);

        $this->eventDispatcher->dispatch(UdpEvent::NAME, $event);
    }

    public function sendDeferredPoint(string $presetName, float $value)
    {
        $point = $this->buildPoint($presetName, $value);
        $event = new DeferredUdpEvent([$point], Database::PRECISION_SECONDS);

        $this->eventDispatcher->dispatch(DeferredUdpEvent::NAME, $event);
    }

    private function buildPoint(string $presetName, float $value): Point
    {
        $preset = $this->getPointPresetByName($presetName);

        $builder = $this->pointBuilderFactory->create();
        $builder
            ->setPreset($preset)
            ->setValue($value)
        ;

        return $builder->build();
    }

    private function getPointPresetByName(string $presetName): PointPresetInterface
    {
        foreach ($this->pointPresets as $pointPreset) {
            if ($pointPreset->getName() === $presetName) {
                return $pointPreset;
            }
        }

        throw new PresetNotFoundException(sprintf('Could not find the preset "%s".', $presetName));
    }
}
