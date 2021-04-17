<?php

namespace PSStoreParsing\Services\APIStore;

use JetBrains\PhpStorm\ArrayShape;
use PSStoreParsing\DTO\APIStoreParams\{Extensions, IVariables, GetCategoriesVariables};
use PSStoreParsing\Exceptions\ApiStore\GetDataException;
use Swoole\Coroutine\Http\Client;

abstract class AbstractApiStore
{
    protected IVariables $variables;
    protected Extensions $extensions;

    protected string $commonPath = '/api/graphql/v1//op';
    protected Client $httpClient;

    public function __construct(Client $httpClient, IVariables $variables, Extensions $extensions)
    {
        $this->variables = $variables;
        $this->extensions = $extensions;
        $this->httpClient = $httpClient;
    }

    public function get(): mixed
    {
        $this->httpClient->setHeaders($this->getHeaders());
        var_dump($this->getPath());
        $result = $this->httpClient->get($this->getPath());

        if (!$result) {
            throw new GetDataException('Error getting result');
        }

        return $this->httpClient->getBody();
    }

    protected function getPath(): string
    {
        return $this->commonPath . '?' . $this->getQueryParams();
    }

    protected function getQueryParams(): string
    {
        $variables = json_encode($this->getVariablesParams(), JSON_THROW_ON_ERROR);
        $extensions = json_encode($this->getExtensionsParams(), JSON_THROW_ON_ERROR);

        $urlData = [
            'operationName' => $this->operationName,
            'variables' => $variables,
            'extensions' => $extensions,
        ];

        return http_build_query($urlData);
    }

    #[ArrayShape(['clientId' => "int", 'alias' => "string"])]
    protected function getVariablesParams(): array
    {
        return [
            'clientId' => $this->variables->getClientId(),
            'alias' => $this->variables->getAlias(),
        ];
    }

    #[ArrayShape(['persistedQuery' => "array"])]
    protected function getExtensionsParams(): array
    {
        return [
            'persistedQuery' => [
                'version' => $this->extensions->getVersion(),
                'sha256Hash' => $this->extensions->getHash(),
            ],
        ];
    }


    #[ArrayShape(['accept-language' => "string", 'x-psn-store-locale-override' => "string"])]
    protected function getHeaders(): array
    {
        return [
            'accept-language' => 'ru-UA',
            'x-psn-store-locale-override' => 'ru-UA',
        ];
    }

    abstract protected function getOperationName(): string;

}
