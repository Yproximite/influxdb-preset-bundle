<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Profile;

interface ProfilePoolInterface
{
    public function addProfile(ProfileInterface $preset): self;

    /**
     * @param array{ name:string, presets:array<string, array{ measurement:string, tags:array<string, string> , fields: array<string, string> }>, connections: array<string, array{ protocol:string, deferred:bool }> } $config
     */
    public function addProfileFromConfig(array $config): self;

    public function getProfileByName(string $profileName): ProfileInterface;
}
