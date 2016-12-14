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
        $this->dispatchEvent(1);
    }
}
