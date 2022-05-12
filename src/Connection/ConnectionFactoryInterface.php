<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Connection;

interface ConnectionFactoryInterface
{
    public function create(): ConnectionInterface;

    /**
     * @param array{ name:string, protocol:string, deferred:bool } $config
     */
    public function createFromConfig(array $config): ConnectionInterface;
}
