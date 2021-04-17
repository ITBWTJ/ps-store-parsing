<?php

namespace PSStoreParsing\Adapters\APIStore;

use PSStoreParsing\DTO\APIStore\Responses\Category;
use PSStoreParsing\DTO\APIStore\Responses\Product;
use PSStoreParsing\Exceptions\ApiStore\GetDataException;
use PSStoreParsing\Exceptions\ApiStore\InvalidArgumentException;

class GetProductFromJsonAdapter implements IGetDataFromJsonAdapter
{
    private string $jsonResponseFromApi;
    private array $responseFromApi;
    private array $data = [];

    private const MAIN_IMAGE_ROLE = 'MASTER';

    public function __construct(string $jsonResponseFromApi)
    {
        $this->jsonResponseFromApi = $jsonResponseFromApi;

        $this->decode();
        $this->parse();
    }

    private function decode(): void
    {
        if (empty($this->jsonResponseFromApi)) {
            throw new InvalidArgumentException('Empty jsonResponseFromApi');
        }

        try {
            $this->responseFromApi = json_decode($this->jsonResponseFromApi, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new InvalidArgumentException('Cant decode jsonResponseFromApi');
        }

    }

    private function parse(): void
    {
        $product = $this->responseFromApi['data']['productRetrieve'] ?? null;

        if (empty($product) || !is_array($product)) {
            throw new InvalidArgumentException('Empty or wrong type of products from api response');
        }

        if (empty($product['webcast'][0])) {
            throw new GetDataException('Empty webcast for product: ' . $product['id']);
        }

        $webcast = $product['webcast'][0];

        $this->data[] = new Product(
            id: $product['id'],
            name: $product['name'],
            type: $product['skus'][0]['name'],
            basePrice: $this->convertStringPriceToInt($webcast['price']['basePrice']),
            discountedPrice: $this->convertStringPriceToInt($webcast['price']['discountedPrice']),
            isExclusive: (bool)$webcast['price']['isExclusive'],
            endTime: (int)$webcast['price']['endTime'],
            concept: $product['concept']['id'],
        );

    }

    /**
     * @return Product[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    private function convertStringPriceToInt(string $price): int
    {
        return (int)str_replace([' ', '.', 'UAH'], '', $price);
    }
}
