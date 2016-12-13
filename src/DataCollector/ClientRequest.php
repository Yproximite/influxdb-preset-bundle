<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\DataCollector;

use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfileInterface;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPresetInterface;

/**
 * Class ClientRequest
 */
class ClientRequest
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
}
