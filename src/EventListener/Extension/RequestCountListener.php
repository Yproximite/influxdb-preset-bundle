<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\EventListener\Extension;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;

/**
 * Class RequestCountListener
 */
final class RequestCountListener extends AbstractListener
{
    public function onKernelTerminate(PostResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->dispatchEvent(1);
    }
}
