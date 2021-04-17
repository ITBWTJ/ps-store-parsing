<?php

namespace PSStoreParsing\Services\APIStore;

use PSStoreParsing\DTO\APIStoreParams\{Extensions, GetCategoriesVariables};
use JetBrains\PhpStorm\Pure;
use Swoole\Coroutine\Http\Client;

class GetCategories extends AbstractApiStore
{
    protected string $operationName = 'getExperience';

    #[Pure] public function __construct(Client $httpClient, GetCategoriesVariables $variables, Extensions $extensions)
    {
        parent::__construct($httpClient, $variables, $extensions);
    }

    protected function getOperationName(): string
    {
        return $this->operationName;
    }
}
