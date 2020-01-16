<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Connection;

/**
 * Class Connection
 */
class Connection implements ConnectionInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var bool
     */
    private $deferred;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ConnectionInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function setProtocol(string $protocol): ConnectionInterface
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function isUdpProtocol(): bool
    {
        return self::PROTOCOL_UDP === $this->getProtocol();
    }

    public function isHttpProtocol(): bool
    {
        return self::PROTOCOL_HTTP === $this->getProtocol();
    }

    public function isDeferred(): bool
    {
        return $this->deferred;
    }

    public function setDeferred(bool $deferred): ConnectionInterface
    {
        $this->deferred = $deferred;

        return $this;
    }
}
