<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\DataCollector;

use Yproximite\Bundle\InfluxDbPresetBundle\Connection\Connection;
use Yproximite\Bundle\InfluxDbPresetBundle\Connection\ConnectionInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPreset;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\Profile;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfileInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;

/**
 * Class ClientRequest
 */
class ClientRequest implements \Serializable
{
    /**
     * @var ProfileInterface
     */
    private $profile;

    /**
     * @var PointPresetInterface
     */
    private $pointPreset;

    /**
     * @var float
     */
    private $value;

    /**
     * @var \DateTimeInterface
     */
    private $dateTime;

    public function __construct(
        ProfileInterface $profile,
        PointPresetInterface $pointPreset,
        float $value,
        \DateTimeInterface $dateTime
    ) {
        $this->profile     = $profile;
        $this->pointPreset = $pointPreset;
        $this->value       = $value;
        $this->dateTime    = $dateTime;
    }

    public function getProfile(): ProfileInterface
    {
        return $this->profile;
    }

    public function getPointPreset(): PointPresetInterface
    {
        return $this->pointPreset;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    public function serialize()
    {
        return serialize([
            $this->value,
            $this->dateTime,
            $this->profile->getName(),
            array_map(function (ConnectionInterface $connection) {
                return $this->serializeConnection($connection);
            }, $this->profile->getConnections()),
            array_map(function (PointPreset $pointPreset) {
                return $this->serializePointPreset($pointPreset);
            }, $this->profile->getPointPresets()),
            $this->serializePointPreset($this->pointPreset),
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->value, $this->dateTime, $profileName, $profileConnections, $profilePointPresets, $pointPreset) = unserialize($serialized);

        $this->pointPreset = $this->unserializePointPreset($pointPreset);

        $this->profile = new Profile();
        $this->profile->setName($profileName);

        foreach ($profileConnections as $connection) {
            $this->profile->addConnection($this->unserializeConnection($connection));
        }

        foreach ($profilePointPresets as $pointPreset) {
            $this->profile->addPointPreset($this->unserializePointPreset($pointPreset));
        }
    }

    protected function serializePointPreset(PointPresetInterface $pointPreset): array
    {
        return [
            $pointPreset->getName(),
            $pointPreset->getFields(),
            $pointPreset->getMeasurement(),
            $pointPreset->getTags(),
        ];
    }

    protected function unserializePointPreset(array $pointPreset): PointPresetInterface
    {
        $newPointPreset = new PointPreset();
        $newPointPreset->setName($pointPreset[0])
            ->setFields($pointPreset[1])
            ->setMeasurement($pointPreset[2])
            ->setTags($pointPreset[3])
        ;

        return $newPointPreset;
    }

    protected function serializeConnection(ConnectionInterface $connection): array
    {
        return [
            $connection->getName(),
            $connection->getProtocol(),
            $connection->isDeferred(),
        ];
    }

    protected function unserializeConnection(array $connection): ConnectionInterface
    {
        $newConnection = new Connection();
        $newConnection->setName($connection[0])
            ->setProtocol($connection[1])
            ->setDeferred($connection[2])
        ;

        return $newConnection;
    }
}
