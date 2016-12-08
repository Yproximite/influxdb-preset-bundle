<?php
declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Connection;

/**
 * Interface ConnectionInterface
 */
interface ConnectionInterface
{
    const PROTOCOL_UDP = 'udp';
    const PROTOCOL_HTTP = 'udp';

    public function getName(): string;

    public function setName(string $name): self;

    public function getProtocol(): string;

    public function setProtocol(string $protocol): self;

    public function isUdpProtocol(): bool;

    public function isHttpProtocol(): bool;

    public function isDeffered(): bool;

    public function setDeffered(bool $deffered): self;
}
