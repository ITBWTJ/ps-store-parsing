<?php

namespace PSStoreParsing\Adapters\APIStore;

use PSStoreParsing\DTO\APIStore\Responses\Category;
use PSStoreParsing\DTO\APIStore\Responses\Product;
use PSStoreParsing\Exceptions\ApiStore\InvalidArgumentException;

class GetProductsFromJsonAdapter implements IGetDataFromJsonAdapter
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
        $products = $this->responseFromApi['data']['categoryGridRetrieve']['products'] ?? null;

        if (empty($products) || !is_array($products)) {
            throw new InvalidArgumentException('Empty or wrong type of products from api response');
        }


        var_dump('COUNT ', count($products));
        foreach ($products as $product) {
            try {
                $mainImage = null;

                if (!empty($product['media']) && is_array($product['media'])) {
                    foreach ($product['media'] as $media) {
                        if (!empty($media['role']) && $media['role'] === self::MAIN_IMAGE_ROLE && !empty($media['url'])) {
                            $mainImage = $media['url'];
                        }
                    }
                }

                $this->data[] = new Product(
                    id: $product['id'],
                    name: $product['name'],
                    type: $product['localizedStoreDisplayClassification'],
                    basePrice: $this->convertStringPriceToInt($product['price']['basePrice']),
                    discountedPrice: $this->convertStringPriceToInt($product['price']['discountedPrice']),
                    isExclusive: $product['price']['isExclusive'],
                    imageUrl: $mainImage,
                    platforms: $product['platforms'] ?? null,
                );
            } catch (\Throwable) {
                echo 'Got error with product: ' . json_encode($product);
            }
        }
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
