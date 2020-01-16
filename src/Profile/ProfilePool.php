<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Profile;

use Yproximite\Bundle\InfluxDbPresetBundle\Exception\ProfileNotFoundException;

/**
 * Class ProfilePool
 */
class ProfilePool implements ProfilePoolInterface
{
    /**
     * @var ProfileFactoryInterface
     */
    private $profileFactory;

    /**
     * @var ProfileInterface[]
     */
    private $profiles = [];

    public function __construct(ProfileFactoryInterface $profileFactory)
    {
        $this->profileFactory = $profileFactory;
    }

    public function addProfile(ProfileInterface $profile): ProfilePoolInterface
    {
        $this->profiles[] = $profile;

        return $this;
    }

    public function addProfileFromConfig(array $config): ProfilePoolInterface
    {
        $profile = $this->profileFactory->createFromConfig($config);

        return $this->addProfile($profile);
    }

    public function getProfileByName(string $profileName): ProfileInterface
    {
        foreach ($this->profiles as $profile) {
            if ($profile->getName() === $profileName) {
                return $profile;
            }
        }

        throw new ProfileNotFoundException(sprintf('Could not find the profile "%s".', $profileName));
    }
}
