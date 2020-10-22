<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Class RequestCountListener
 */
final class RequestCountListener extends AbstractListener
{
    public function onKernelTerminate(TerminateEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->dispatchEvent(1);
    }
}
