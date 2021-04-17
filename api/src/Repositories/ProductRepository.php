<?php

namespace PSStoreParsing\Repositories;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use PDO;
use PSStoreParsing\DTO\APIStore\Responses\Product as ProductDTO;
use PSStoreParsing\Models\Product;
use Swoole\Database\PDOPool;
use Swoole\Database\PDOProxy;

class ProductRepository
{
    private PDOPool $mysqlPool;
    private PDO|PDOProxy $client;
    private string $table = 'games';

    public function __construct(
        PDOPool $mysql
    ) {

        $this->mysqlPool = $mysql;
        $this->client = $this->mysqlPool->get();
    }


    /**
     * @return Product[]|null
     */
    public function all(): ?array
    {
        $statement = $this->client->prepare("SELECT * FROM {$this->table} where deleted_at is null");
        $statement->execute();

        $data = $statement->fetchAll(mode: PDO::FETCH_ASSOC);

        if ($data) {
            return $this->getModels($data);
        }

        return null;
    }

    public function createFromDTO(ProductDTO $DTO): Product
    {
        $sql = $this->getInsertIntoSQL();
        $sth = $this->client->prepare($sql);

        $data = $this->DTOToArrayFields($DTO);

        var_dump($sql, $data);
        $sth->execute($data);

        $data['id'] = $this->client->lastInsertId();

        return $this->getModel($data);
    }

    public function updateFromDTO(Product $model, ProductDTO $DTO): Product
    {
        $sql = $this->getInsertIntoSQL();
        $sth = $this->client->prepare($sql);

        $data = $this->DTOToArrayFields($DTO);
        $data['id'] = $model->getId();

        $sth->execute($data);

        return $this->getModel($data);
    }

    private function getModels(array $data): array
    {
        $models = [];

        foreach ($data as $modelData) {
            $models[] = $this->getModel($modelData);
        }

        return $models;
    }

    private function getModel(array $modelData): Product
    {
        return new Product(
            id: (int)$modelData['id'],
            name: $modelData['name'],
            storeId: $modelData['store_id'],
            type: $modelData['type'],
            basePrice: $modelData['base_price'],
            discountedPrice: $modelData['discounted_price'],
            endTime: $modelData['end_time'],
            imageUrl: $modelData['image_url'],
            isExclusive: $modelData['is_exclusive'],
            platforms: !empty($modelData['platforms']) ? json_decode($modelData['platforms'], true) : [],
            concept: $modelData['concept'],
        );
    }

    #[ArrayShape([
        'name' => "string",
        'store_id' => "string",
        'image_url' => "null|string",
        'type' => "string",
        'base_price' => "int",
        'discounted_price' => "int|null",
        'is_exclusive' => "bool",
        'end_time' => "int|null",
        'platforms' => "string",
        'concept' => 'string|null'
    ])]
    private function DTOToArrayFields(ProductDTO $DTO): array
    {
        return [
            'name' => $DTO->getName(),
            'store_id' => $DTO->getId(),
            'image_url' => $DTO->getImageUrl(),
            'type' => $DTO->getType(),
            'base_price' => $DTO->getBasePrice(),
            'discounted_price' => $DTO->getDiscountedPrice(),
            'is_exclusive' => (int)$DTO->isExclusive(),
            'end_time' => $DTO->getEndTime(),
            'platforms' => json_encode($DTO->getPlatforms(), JSON_THROW_ON_ERROR),
            'concept' => $DTO->getConcept(),
        ];
    }

    private function getInsertIntoSQL(): string
    {
        return <<<'SQL'
            INSERT INTO `games` SET
            `store_id` = :store_id,
            `name` = :name,
            `type` = :type,
            `base_price` = :base_price,
            `discounted_price` = :discounted_price,
            `end_time` = :end_time,
            `image_url` = :image_url,
            `is_exclusive` = :is_exclusive,
            `platforms` = :platforms,
            `concept` = :consept
SQL;
    }

    private function getUpdateSQL(): string
    {
        return <<<'SQL'
            UPDATE `games` SET
                `store_id` = :store_id,
                `name` = :name,
                `type` = :type,
                `base_price` = :base_price,
                `discounted_price` = :discounted_price,
                `end_time` = :end_time,
                `image_url` = :image_url,
                `is_exclusive` = :is_exclusive,
                `platforms` = :platforms,
                `concept` = :consept
            WHERE `id` = :id
SQL;
    }
}
