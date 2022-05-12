<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\HttpKernel\Event\TerminateEvent;

final class RequestCountListener extends AbstractListener
{
    public function onKernelTerminate(TerminateEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->dispatchEvent(1);
    }
}
