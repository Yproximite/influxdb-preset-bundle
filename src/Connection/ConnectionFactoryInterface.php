<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Connection;

/**
 * Interface ConnectionFactoryInterface
 */
interface ConnectionFactoryInterface
{
    public function create(): ConnectionInterface;

    public function createFromConfig(array $config): ConnectionInterface;
}
