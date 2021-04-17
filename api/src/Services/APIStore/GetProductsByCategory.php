<?php

namespace PSStoreParsing\Services\APIStore;

use PSStoreParsing\DTO\APIStoreParams\{Extensions, GetProductsByCategoryVariables};
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Swoole\Coroutine\Http\Client;

class GetProductsByCategory extends AbstractApiStore
{
    protected string $operationName = 'categoryGridRetrieve';

    #[Pure] public function __construct(Client $httpClient, GetProductsByCategoryVariables $variables, Extensions $extensions)
    {
        parent::__construct($httpClient, $variables, $extensions);
        $this->variables = $variables;
    }

    protected function getOperationName(): string
    {
        return $this->operationName;
    }


    #[ArrayShape([
        'id' => "string",
        'pageArgs' => "array",
        'sortBy' => "array",
        'filterBy' => "array",
        'facetOptions' => "array"
    ])]
    protected function getVariablesParams(): array
    {
        return [
            'id' => $this->variables->getId(),
            'pageArgs' => [
                'size' => $this->variables->getSize(),
                'offset' => $this->variables->getOffset(),
            ],
            'sortBy' => [
                'name' => 'default',
                'isAscending' => true,
            ],
            'filterBy' => [],
            'facetOptions' => [],
        ];
    }
}
