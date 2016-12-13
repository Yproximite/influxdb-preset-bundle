<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;

/**
 * Class ResponseTimeListener
 */
final class ResponseTimeListener extends AbstractListener
{
    public function onKernelTerminate(PostResponseEvent $event)
    {
        $request   = $event->getRequest();
        $startTime = $request->server->get('REQUEST_TIME_FLOAT', $request->server->get('REQUEST_TIME'));
        $time      = microtime(true) - $startTime;
        $time      = round($time * 1000);

        $this->dispatchEvent($time);
    }
}
