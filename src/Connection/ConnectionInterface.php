<?php

declare(strict_types=1);

namespace Yproximite\Bundle\InfluxDbPresetBundle\Connection;

interface ConnectionInterface
{
    public const PROTOCOL_UDP  = 'udp';
    public const PROTOCOL_HTTP = 'http';

    public function getName(): string;

    public function setName(string $name): self;

    public function getProtocol(): string;

    public function setProtocol(string $protocol): self;

    public function isUdpProtocol(): bool;

    public function isHttpProtocol(): bool;

    public function isDeferred(): bool;

    public function setDeferred(bool $deferred): self;
}
