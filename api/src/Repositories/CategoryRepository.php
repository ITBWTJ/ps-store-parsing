<?php

namespace PSStoreParsing\Repositories;

use JetBrains\PhpStorm\Pure;
use PDO;
use PSStoreParsing\DTO\APIStore\Responses\Category as CategoryDTO;
use PSStoreParsing\Models\Category;
use Swoole\Database\PDOPool;
use Swoole\Database\PDOProxy;

class CategoryRepository
{
    private PDOPool $mysqlPool;
    private PDO|PDOProxy $client;
    private string $table = 'categories';

    public function __construct(
        PDOPool $mysql
    ) {

        $this->mysqlPool = $mysql;
        $this->client = $this->mysqlPool->get();
    }

    /**
     * @return Category[]|null
     */
    public function all()
    {
        $statement = $this->client->prepare("SELECT * FROM {$this->table} where deleted_at is null");
        $statement->execute();

        $data = $statement->fetchAll(mode: PDO::FETCH_ASSOC);

        if ($data) {
            return $this->getModels($data);
        }

        return null;
    }

    public function createFromDTO(CategoryDTO $category): Category
    {
        $sth = $this->client->prepare("INSERT INTO `{$this->table}` SET `name` = :name, `store_id` = :store_id, 
                        `image_url` = :image_url, `link_target` = :link_target");

        $data = [
            'name' => $category->getName(),
            'store_id' => $category->getId(),
            'image_url' => $category->getImageUrl(),
            'link_target' => $category->getLinkTarget(),
        ];

        $sth->execute($data);

        $data['id'] = $this->client->lastInsertId();

        return $this->getModel($data);
    }

    private function getModels(array $data): array
    {
        $models = [];

        foreach ($data as $modelData) {
            $models[] = new Category(
                id: (int)$modelData['id'],
                name: $modelData['name'],
                storeId: $modelData['store_id'],
                imageUrl: $modelData['image_url'],
                linkTarget: $modelData['link_target'],
            );
        }

        return $models;
    }

    #[Pure] private function getModel(array $modelData): Category
    {
        return new Category(
            id: (int)$modelData['id'],
            name: $modelData['name'],
            storeId: $modelData['store_id'],
            imageUrl: $modelData['image_url'],
            linkTarget: $modelData['link_target'],
        );
    }
}
