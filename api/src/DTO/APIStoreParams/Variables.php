<?php

namespace PSStoreParsing\DTO\APIStoreParams;

class Variables
{
    private int $clientId;
    private string $alias;

    public function __construct(int $clientId, string $alias)
    {
        $this->clientId = $clientId;
        $this->alias = $alias;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
