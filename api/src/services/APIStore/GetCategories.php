<?php

namespace PSStoreParsing\Services\APIStore;

use JetBrains\PhpStorm\ArrayShape;
use PSStoreParsing\DTO\APIStoreParams\{Extensions, Variables};
use PSStoreParsing\Exceptions\ApiStore\GetCategoriesException;

class GetCategories
{
    private Variables $variables;
    private Extensions $extensions;

    private string $operationName = 'categoryGridRetrieve';
    private string $commonPath = '/api/graphql/v1//op';
    private \Swoole\Coroutine\Http\Client $httpClient;

    public function __construct(\Swoole\Coroutine\Http\Client $httpClient, Variables $variables, Extensions $extensions)
    {
        $this->variables = $variables;
        $this->extensions = $extensions;
        $this->httpClient = $httpClient;
    }

    public function get(): mixed
    {
        $this->httpClient->setHeaders($this->getHeaders());
        $result = $this->httpClient->get($this->getPath());
        var_dump($this->getPath());
        if (!$result) {
            throw new GetCategoriesException('Error getting result');
        }

        return $this->httpClient->getBody();
    }

    private function getQueryParams(): string
    {
        $variables = json_encode($this->getVariablesParams());
        $extensions = json_encode($this->getExtensionsParams());

        $urlData = [
            'operationName' => $this->operationName,
            'variables' => $variables,
            'extensions' => $extensions,
        ];

        return http_build_query($urlData);
    }

    #[ArrayShape(['clientId' => "int", 'alias' => "string"])]
    private function getVariablesParams(): array
    {
        return [
            'clientId' => $this->variables->getClientId(),
            'alias' => $this->variables->getAlias(),
        ];
    }

    #[ArrayShape(['version' => "int", 'sha256Hash' => "string"])]
    private function getExtensionsParams(): array
    {
        return [
            'version' => $this->extensions->getVersion(),
            'sha256Hash' => $this->extensions->getHash(),
        ];
    }


    #[ArrayShape(['accept-language' => "string", 'x-psn-store-locale-override' => "string"])]
    private function getHeaders(): array
    {
        return [
            'accept-language' => 'ru-UA',
            'x-psn-store-locale-override' => 'ru-UA',
        ];
    }

    private function getPath(): string
    {
        return $this->commonPath . '?' . $this->getQueryParams();
    }
}
