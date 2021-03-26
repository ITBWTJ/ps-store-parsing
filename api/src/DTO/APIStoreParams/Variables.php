<?php

namespace PSStoreParsing\DTO\APIStoreParams;

class Variables
{
    private string $clientId;
    private string $alias;

    public function __construct(string $clientId, string $alias)
    {
        $this->clientId = $clientId;
        $this->alias = $alias;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
