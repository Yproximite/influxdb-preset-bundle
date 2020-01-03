<?php

declare(strict_types=1);

namespace spec\Yproximite\Bundle\InfluxDbPresetBundle\Point;

use PhpSpec\ObjectBehavior;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointBuilder;
use Yproximite\Bundle\InfluxDbPresetBundle\Point\PointPreset;

class PointBuilderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(PointBuilder::class);
    }

    public function it_should_build_simple_point()
    {
        $pointPreset = new PointPreset();
        $pointPreset->setMeasurement('apples');

        $dateTime = new \DateTime();

        $this->setValue(5.);
        $this->setPreset($pointPreset);
        $this->setDateTime($dateTime);

        $this->build()->__toString()->shouldBeEqualTo(sprintf('apples value=5 %d', $dateTime->getTimestamp()));
    }

    public function it_should_build_advanced_point()
    {
        $pointPreset = new PointPreset();
        $pointPreset
            ->setMeasurement('apples')
            ->setTags(['a' => '<value>', 'b' => 456])
            ->setFields(['c' => '#<value>#', 'd' => false])
        ;

        $dateTime = new \DateTime();

        $this->setValue(5.);
        $this->setPreset($pointPreset);
        $this->setDateTime($dateTime);

        $this->build()->__toString()->shouldBeEqualTo(sprintf('apples,a=5,b=456 c="#5#",d=false,value=5 %d', $dateTime->getTimestamp()));
    }
}
