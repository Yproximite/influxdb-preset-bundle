<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\HttpKernel\Event\TerminateEvent;

final class MemoryListener extends AbstractListener
{
    public function onKernelTerminate(TerminateEvent $event): void
    {
        $memory = memory_get_peak_usage(true);
        $memory = $memory > 1024 ? \intval($memory / 1024) : 0;

        $this->dispatchEvent($memory);
    }
}
