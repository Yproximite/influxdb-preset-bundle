<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Profile;

/**
 * Interface ProfilePoolInterface
 */
interface ProfilePoolInterface
{
    public function addProfile(ProfileInterface $preset): self;

    public function addProfileFromConfig(array $config): self;

    public function getProfileByName(string $profileName): ProfileInterface;
}
