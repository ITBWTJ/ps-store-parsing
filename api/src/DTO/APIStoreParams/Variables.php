<?php

namespace PSStoreParsing\DTO\APIStoreParams;

use DI\Annotation\Inject;

class Variables
{
    private string $clientId;
    private string $alias;

    /**
     * Variables constructor.
     * @param string $clientId
     * @param string $alias
     *
     * @Inject({"ps-store.api-client-id", "ps-store.api-alias"})
     */
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
