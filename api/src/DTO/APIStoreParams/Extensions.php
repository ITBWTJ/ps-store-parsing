<?php

namespace PSStoreParsing\DTO\APIStoreParams;

class Extensions
{
    private int $version;
    private string $hash;

    public function __construct(int $version, string $hash)
    {
        $this->version = $version;
        $this->hash = $hash;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getHash(): string
    {
       return $this->hash;
    }
}
