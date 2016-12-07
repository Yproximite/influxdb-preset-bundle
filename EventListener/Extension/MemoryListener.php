<?php

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;

/**
 * Class MemoryListener
 */
final class MemoryListener extends AbstractListener
{
    public function onKernelTerminate(PostResponseEvent $event)
    {
        $memory = memory_get_peak_usage(true);
        $memory = $memory > 1024 ? intval($memory / 1024) : 0;

        $this->dispatchEvent($memory);
    }
}
