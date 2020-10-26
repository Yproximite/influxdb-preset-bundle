<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Class ResponseTimeListener
 */
final class ResponseTimeListener extends AbstractListener
{
    public function onKernelTerminate(TerminateEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request   = $event->getRequest();
        $startTime = $request->server->get('REQUEST_TIME_FLOAT', $request->server->get('REQUEST_TIME'));
        $time      = microtime(true) - $startTime;
        $time      = round($time * 1000);

        $this->dispatchEvent($time);
    }
}
