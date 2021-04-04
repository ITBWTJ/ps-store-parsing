<?php

namespace PSStoreParsing\DTO\APIStoreParams;

use DI\Annotation\Inject;

class Extensions
{
    private int $version;
    private string $hash;

    /**
     * Extensions constructor.
     * @param int $version
     * @param string $hash
     *
     * @Inject({"ps-store.api-version", "ps-store.api-hash"})
     */
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
