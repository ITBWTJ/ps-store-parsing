<?php

namespace PSStoreParsing\Services\APIStore;

use PSStoreParsing\DTO\APIStoreParams\{Extensions, GetProductByIdVariables};
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Swoole\Coroutine\Http\Client;

class GetProductById extends AbstractApiStore
{
    protected string $operationName = 'productRetrieveForCtasWithPrice';

    #[Pure] public function __construct(Client $httpClient, GetProductByIdVariables $variables, Extensions $extensions)
    {
        parent::__construct($httpClient, $variables, $extensions);
        $this->variables = $variables;
    }

    protected function getOperationName(): string
    {
        return $this->operationName;
    }


    #[ArrayShape([
        'productId' => "string",
    ])]
    protected function getVariablesParams(): array
    {
        return [
            'productId' => $this->variables->getId(),
        ];
    }
}
