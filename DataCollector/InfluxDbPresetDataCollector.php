<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

use Yproximite\Bundle\InfluxDbPresetBundle\Event\ClientRequestEvent;
use Yproximite\Bundle\InfluxDbPresetBundle\Profile\ProfilePoolInterface;

/**
 * Class InfluxDbPresetDataCollector
 */
final class InfluxDbPresetDataCollector extends DataCollector
{
    /**
     * @var ProfilePoolInterface
     */
    private $profilePool;

    /**
     * @var ClientRequest[]
     */
    private $requests = [];

    public function __construct(ProfilePoolInterface $profilePool)
    {
        $this->profilePool = $profilePool;
    }

    public function onClientRequest(ClientRequestEvent $event)
    {
        $profile = $this->profilePool->getProfileByName($event->getProfileName());
        $preset  = $profile->getPointPresetByName($event->getPresetName());

        $this->requests[] = new ClientRequest($profile, $preset, $event->getValue());
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'requests' => $this->requests,
        ];
    }

    /**
     * @return ClientRequest[]
     */
    public function getRequests(): array
    {
        return $this->data['requests'];
    }

    public function getName()
    {
        return 'yproximite.influxdb_preset';
    }
}
