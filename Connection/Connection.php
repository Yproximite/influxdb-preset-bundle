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
    private $deffered;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function setProtocol(string $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function isUdpProtocol(): bool
    {
        return $this->getProtocol() === self::PROTOCOL_UDP;
    }

    public function isHttpProtocol(): bool
    {
        return $this->getProtocol() === self::PROTOCOL_HTTP;
    }

    public function isDeffered(): bool
    {
        return $this->deffered;
    }

    public function setDeffered(bool $deffered): self
    {
        $this->deffered = $deffered;

        return $this;
    }
}
