<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Connection;

/**
 * Class ConnectionFactory
 */
class ConnectionFactory implements ConnectionFactoryInterface
{
    public function create(): ConnectionInterface
    {
        return new Connection();
    }

    public function createFromConfig(array $config): ConnectionInterface
    {
        $connection = $this->create();
        $connection
            ->setName($config['name'])
            ->setProtocol($config['protocol'])
            ->setDeferred($config['deferred'])
        ;

        return $connection;
    }
}
