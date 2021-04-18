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

        if (empty($product['webctas'][0])) {
            throw new GetDataException('Empty webctas for product: ' . $product['id']);
        }

        $webctas = $product['webctas'][0];

        $this->data[] = new Product(
            id: $product['id'],
            name: $product['name'],
            type: $product['skus'][0]['name'],
            basePrice: $this->convertStringPriceToInt($webctas['price']['basePrice']),
            discountedPrice: $this->convertStringPriceToInt($webctas['price']['discountedPrice']),
            isExclusive: (bool)$webctas['price']['isExclusive'],
            endTime: $this->convertToSeconds($webctas['price']['endTime']),
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

    private function convertToSeconds(?string $miliseconds): ?int
    {
        if ($miliseconds) {
            return ((int)$miliseconds) / 1000;
        }

        return null;
    }
}
