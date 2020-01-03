<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Profile;

/**
 * Interface ProfileFactoryInterface
 */
interface ProfileFactoryInterface
{
    public function create(): ProfileInterface;

    public function createFromConfig(array $config): ProfileInterface;
}
