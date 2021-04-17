<?php

namespace PSStoreParsing\DTO\APIStoreParams;

use PSStoreParsing\Exceptions\ApiStore\InvalidArgumentException;

class GetProductByIdVariables implements IVariables
{
    public function __construct(
        private string $id
    ) {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
