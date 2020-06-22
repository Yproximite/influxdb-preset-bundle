<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Event;

if (class_exists('\Symfony\Contracts\EventDispatcher\Event')) {
    class Event extends \Symfony\Contracts\EventDispatcher\Event
    {
    }
} else {
    class Event extends \Symfony\Component\EventDispatcher\Event
    {
    }
}
